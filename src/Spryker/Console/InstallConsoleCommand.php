<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Console;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Install\Exception\InstallException;
use Spryker\Install\InstallFacade;
use Spryker\Install\InstallFactory;
use Spryker\Style\SprykerStyle;
use Spryker\Style\StyleInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallConsoleCommand extends Command
{
    public const ARGUMENT_ENVIRONMENT = 'environment';
    public const ARGUMENT_STORE = 'store';

    public const OPTION_RECIPE = 'recipe';
    public const OPTION_RECIPE_SHORT = 'r';

    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_DRY_RUN_SHORT = 'd';

    public const OPTION_SECTIONS = 'sections';
    public const OPTION_SECTIONS_SHORT = 's';

    public const OPTION_GROUPS = 'groups';
    public const OPTION_GROUPS_SHORT = 'g';

    public const OPTION_EXCLUDE = 'exclude';
    public const OPTION_EXCLUDE_SHORT = 'x';

    public const OPTION_INCLUDE_EXCLUDED = 'include-excluded';
    public const OPTION_INCLUDE_EXCLUDED_SHORT = 'e';

    public const OPTION_INTERACTIVE = 'interactive';
    public const OPTION_INTERACTIVE_SHORT = 'i';

    public const OPTION_BREAKPOINT = 'breakpoint';
    public const OPTION_BREAKPOINT_SHORT = 'b';

    public const OPTION_LOG = 'log';
    public const OPTION_LOG_SHORT = 'l';

    public const OPTION_ASK_BEFORE_CONTINUE = 'ask-before-continue';
    public const OPTION_ASK_BEFORE_CONTINUE_SHORT = 'a';

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
     * @var \Spryker\Install\Configuration\ConfigurationInterface
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
        $environment = $this->getEnvironment();
        $this->setName('install')
            ->setDescription('Run install for a specified environment.')
            ->addArgument(static::ARGUMENT_STORE, InputArgument::OPTIONAL, 'Name of the store for which the install should be run.')
            ->addOption(static::OPTION_RECIPE, static::OPTION_RECIPE_SHORT, InputOption::VALUE_REQUIRED, 'Name of the recipe you want to use for install.', $environment)
            ->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Dry runs the install, no command will be executed.')
            ->addOption(static::OPTION_SECTIONS, static::OPTION_SECTIONS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of sections(s) to be executed. A section is a set of commands related to one topic.')
            ->addOption(static::OPTION_GROUPS, static::OPTION_GROUPS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of group(s) to be executed. If command has no group(s) it will not be executed when this option is set. A group is a set of commands grouped together regardless their topic.')
            ->addOption(static::OPTION_EXCLUDE, static::OPTION_EXCLUDE_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of section(s) or group(s) to be excluded from execution.')
            ->addOption(static::OPTION_INCLUDE_EXCLUDED, static::OPTION_INCLUDE_EXCLUDED_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Include command(s)/section(s) which are marked as excluded in the configuration.')
            ->addOption(static::OPTION_INTERACTIVE, static::OPTION_INTERACTIVE_SHORT, InputOption::VALUE_NONE, 'If set, console will ask for each section interactively if the section should be executed.')
            ->addOption(static::OPTION_BREAKPOINT, static::OPTION_BREAKPOINT_SHORT, InputOption::VALUE_NONE, 'If set, the console application is in debug mode. Execution stops after each command and waits to continue until confirmed.')
            ->addOption(static::OPTION_ASK_BEFORE_CONTINUE, static::OPTION_ASK_BEFORE_CONTINUE_SHORT, InputOption::VALUE_NONE, 'By default the script will continue when a command failed. If set, the console will ask before it continues.')
            ->addOption(static::OPTION_LOG, static::OPTION_LOG_SHORT, InputOption::VALUE_NONE, 'When this option is used all steps from the install process will be logged.');
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

        $this->getFacade()->runInstall(
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
    protected function createOutput(InputInterface $input, OutputInterface $output): StyleInterface
    {
        $shouldLog = $input->getOption(static::OPTION_LOG);

        return new SprykerStyle(
            $input,
            $output,
            $this->getFactory()->createTimer(),
            ($shouldLog) ? $this->getFactory()->createOutputLogger() : null
        );
    }

    /**
     * @return \Spryker\Install\CommandLine\CommandLineArgumentContainer
     */
    protected function getCommandLineArgumentContainer(): CommandLineArgumentContainer
    {
        return new CommandLineArgumentContainer(
            $this->getOptionAsString(self::ARGUMENT_STORE)
        );
    }

    /**
     * @return \Spryker\Install\CommandLine\CommandLineOptionContainer
     */
    protected function getCommandLineOptionContainer(): CommandLineOptionContainer
    {
        return new CommandLineOptionContainer(
            $this->getOptionAsString(self::OPTION_RECIPE),
            $this->getSectionsToBeExecuted(),
            $this->getGroupsToBeExecuted(),
            $this->getExcludedStagesAndExcludedGroups(),
            $this->getIncludeExcluded(),
            (bool)$this->input->getOption(static::OPTION_INTERACTIVE),
            (bool)$this->input->getOption(static::OPTION_DRY_RUN),
            (bool)$this->input->getOption(static::OPTION_BREAKPOINT),
            (bool)$this->input->getOption(static::OPTION_ASK_BEFORE_CONTINUE)
        );
    }

    /**
     * @return array
     */
    protected function getSectionsToBeExecuted(): array
    {
        return $this->getOptionAndComment(static::OPTION_SECTIONS, 'Install will only run this section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getGroupsToBeExecuted(): array
    {
        return $this->getOptionAndComment(static::OPTION_GROUPS, 'Install will only run this group(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getExcludedStagesAndExcludedGroups()
    {
        return $this->getOptionAndComment(static::OPTION_EXCLUDE, 'Install will exclude this group(s) or section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getIncludeExcluded(): array
    {
        return $this->getOptionAndComment(static::OPTION_INCLUDE_EXCLUDED, 'Install will include this excluded section(s) or command(s) "%s"');
    }

    /**
     * @param string $optionKey
     * @param string $commentPattern
     *
     * @return array
     */
    protected function getOptionAndComment($optionKey, $commentPattern): array
    {
        $option = (array)$this->input->getOption($optionKey);

        if (count($option) > 0) {
            $this->output->note(sprintf($commentPattern, implode(', ', $option)));
        }

        return $option;
    }

    /**
     * @return \Spryker\Install\InstallFacade
     */
    protected function getFacade(): InstallFacade
    {
        return new InstallFacade();
    }

    /**
     * @return \Spryker\Install\InstallFactory
     */
    protected function getFactory(): InstallFactory
    {
        return new InstallFactory();
    }

    /**
     * @return string
     */
    protected function getEnvironment()
    {
        $environment = (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');

        return (string)$environment;
    }

    /**
     * @param string $optionName
     *
     * @throws \Spryker\Install\Exception\InstallException
     *
     * @return string
     */
    protected function getOptionAsString(string $optionName): string
    {
        $recipeOptionValue = $this->input->getOption($optionName);

        if (!is_string($recipeOptionValue)) {
            throw new InstallException(
                sprintf(
                    'Value of `%s` option should return `STRING` type. Return `%s`.',
                    $optionName,
                    strtoupper(gettype($recipeOptionValue))
                )
            );
        }

        return $recipeOptionValue;
    }
}
