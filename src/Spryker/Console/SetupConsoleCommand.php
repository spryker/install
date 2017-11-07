<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Console;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\SetupFacade;
use Spryker\Style\SprykerStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupConsoleCommand extends Command
{
    const ARGUMENT_ENVIRONMENT = 'environment';
    const ARGUMENT_STORE = 'store';

    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_DRY_RUN_SHORT = 'd';

    const OPTION_SECTIONS = 'sections';
    const OPTION_SECTIONS_SHORT = 's';

    const OPTION_GROUPS = 'groups';
    const OPTION_GROUPS_SHORT = 'g';

    const OPTION_EXCLUDE = 'exclude';
    const OPTION_EXCLUDE_SHORT = 'x';

    const OPTION_INCLUDE_EXCLUDED = 'include-excluded';
    const OPTION_INCLUDE_EXCLUDED_SHORT = 'a';

    const OPTION_INTERACTIVE = 'interactive';
    const OPTION_INTERACTIVE_SHORT = 'i';

    const OPTION_BREAKPOINT = 'breakpoint';
    const OPTION_BREAKPOINT_SHORT = 'b';

    const OPTION_ASK_BEFORE_CONTINUE = 'ask-before-continue';
    const OPTION_ASK_BEFORE_CONTINUE_SHORT = 'e';

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Spryker\Style\StyleInterface
     */
    protected $output;

    /**
     * @var bool
     */
    protected $isDryRun;

    /**
     * @var \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $commandExitCodes = [];

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('setup')
            ->setDescription('Run setup for a specified environment.')
            ->addArgument(static::ARGUMENT_ENVIRONMENT, InputArgument::OPTIONAL, 'Name of the environment for which the setup should be run.', 'development')
            ->addArgument(static::ARGUMENT_STORE, InputArgument::OPTIONAL, 'Name of the store for which the setup should be run.')
            ->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Dry runs the setup, no command will be executed.')
            ->addOption(static::OPTION_SECTIONS, static::OPTION_SECTIONS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of sections(s) to be executed. A section is a set of commands related to one topic.')
            ->addOption(static::OPTION_GROUPS, static::OPTION_GROUPS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of group(s) to be executed. If command has no group(s) it will not be executed when this option is set. A group is a set of commands grouped together regardless their topic.')
            ->addOption(static::OPTION_EXCLUDE, static::OPTION_EXCLUDE_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of section(s) or group(s) to be excluded from execution.')
            ->addOption(static::OPTION_INCLUDE_EXCLUDED, static::OPTION_INCLUDE_EXCLUDED_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Include command(s)/section(s) which are marked as excluded in the configuration.')
            ->addOption(static::OPTION_INTERACTIVE, static::OPTION_INTERACTIVE_SHORT, InputOption::VALUE_NONE, 'If set, console will ask for each section interactively if the section should be executed.')
            ->addOption(static::OPTION_BREAKPOINT, static::OPTION_BREAKPOINT_SHORT, InputOption::VALUE_NONE, 'If set, the console application is in debug mode. Execution stops after each command and waits to continue until confirmed.')
            ->addOption(static::OPTION_ASK_BEFORE_CONTINUE, static::OPTION_ASK_BEFORE_CONTINUE_SHORT, InputOption::VALUE_NONE, 'By default the script will continue when a command failed. If set, the console will ask before it continues.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $this->createOutput($input, $output);

        $this->getFacade()->runSetup(
            $this->getCommandLineArgumentContainer(),
            $this->getCommandLineOptionContainer(),
            $this->output
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Style\StyleInterface
     */
    protected function createOutput(InputInterface $input, OutputInterface $output)
    {
        return new SprykerStyle($input, $output);
    }

    /**
     * @return \Spryker\Setup\CommandLine\CommandLineArgumentContainer
     */
    protected function getCommandLineArgumentContainer()
    {
        return new CommandLineArgumentContainer(
            $this->input->getArgument(static::ARGUMENT_ENVIRONMENT),
            $this->input->getArgument(static::ARGUMENT_STORE)
        );
    }

    /**
     * @return \Spryker\Setup\CommandLine\CommandLineOptionContainer
     */
    protected function getCommandLineOptionContainer()
    {
        return new CommandLineOptionContainer(
            $this->getSectionsToBeExecuted(),
            $this->getGroupsToBeExecuted(),
            $this->getExcludedStagesAndExcludedGroups(),
            $this->getIncludeExcluded(),
            $this->input->getOption(static::OPTION_INTERACTIVE),
            $this->input->getOption(static::OPTION_DRY_RUN),
            $this->input->getOption(static::OPTION_BREAKPOINT),
            $this->input->getOption(static::OPTION_ASK_BEFORE_CONTINUE)
        );
    }

    /**
     * @return array
     */
    protected function getSectionsToBeExecuted()
    {
        return $this->getOptionAndComment(static::OPTION_SECTIONS, 'Setup will only run this section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getGroupsToBeExecuted()
    {
        return $this->getOptionAndComment(static::OPTION_GROUPS, 'Setup will only run this group(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getExcludedStagesAndExcludedGroups()
    {
        return $this->getOptionAndComment(static::OPTION_EXCLUDE, 'Setup will exclude this group(s) or section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getIncludeExcluded()
    {
        return $this->getOptionAndComment(static::OPTION_INCLUDE_EXCLUDED, 'Setup will include this excluded section(s) or command(s) "%s"');
    }

    /**
     * @param string $optionKey
     * @param string $commentPattern
     *
     * @return mixed
     */
    protected function getOptionAndComment($optionKey, $commentPattern)
    {
        $option = $this->input->getOption($optionKey);

        if (count($option) > 0) {
            $this->output->note(sprintf($commentPattern, implode(', ', $option)));
        }

        return $option;
    }

    /**
     * @return \Spryker\Setup\SetupFacade
     */
    protected function getFacade()
    {
        return new SetupFacade();
    }
}
