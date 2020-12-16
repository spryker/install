<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Console;

use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Exception\InstallException;
use Spryker\Zed\Install\Communication\Style\StyleInterface;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Install\Business\InstallFacadeInterface getFacade()
 * @method \Spryker\Zed\Install\Communication\InstallCommunicationFactory getFactory()
 */
class InstallConsole extends Console
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
     * @var \Spryker\Zed\Install\Communication\Style\StyleInterface
     */
    protected $output;

    /**
     * @var bool
     */
    protected $isDryRun;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $environment = $this->getFactory()->getConfig()->getEnvironment();
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
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $this->createOutput($input, $output);

        $this->getFacade()->runInstall(
            $this->getCommandLineArgumentContainer(),
            $this->getCommandLineOptionContainer(),
            $this->output
        );

        return 0;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Communication\Style\StyleInterface
     */
    protected function createOutput(InputInterface $input, OutputInterface $output): StyleInterface
    {
        $shouldLog = $input->getOption(static::OPTION_LOG);

        if ($shouldLog) {
            return $this->getFactory()->createLoggableStyle($input, $output);
        }

        return $this->getFactory()->createStyle($input, $output);
    }

    /**
     * @throws \Spryker\Zed\Install\Communication\Exception\InstallException
     *
     * @return \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer
     */
    protected function getCommandLineArgumentContainer(): CommandLineArgumentContainer
    {
        $store = $this->input->getArgument(self::ARGUMENT_STORE);

        if ($store !== null && !is_string($store)) {
            throw new InstallException(
                sprintf(
                    'Value of `%s` argument should return `string|null` type. Return type is `%s`.',
                    self::ARGUMENT_STORE,
                    gettype($store)
                )
            );
        }

        return new CommandLineArgumentContainer($store);
    }

    /**
     * @throws \Spryker\Zed\Install\Communication\Exception\InstallException
     *
     * @return \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer
     */
    protected function getCommandLineOptionContainer(): CommandLineOptionContainer
    {
        $recipeOption = $this->input->getOption(self::OPTION_RECIPE);

        if (!is_string($recipeOption)) {
            throw new InstallException(
                sprintf(
                    'Value of `%s` option should return `string` type. Return `%s`.',
                    self::OPTION_RECIPE,
                    gettype($recipeOption)
                )
            );
        }

        return new CommandLineOptionContainer(
            $recipeOption,
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
     * @return string[]
     */
    protected function getSectionsToBeExecuted(): array
    {
        return $this->getOptionAndComment(static::OPTION_SECTIONS, 'Install will only run this section(s) "%s"');
    }

    /**
     * @return string[]
     */
    protected function getGroupsToBeExecuted(): array
    {
        return $this->getOptionAndComment(static::OPTION_GROUPS, 'Install will only run this group(s) "%s"');
    }

    /**
     * @return string[]
     */
    protected function getExcludedStagesAndExcludedGroups(): array
    {
        return $this->getOptionAndComment(static::OPTION_EXCLUDE, 'Install will exclude this group(s) or section(s) "%s"');
    }

    /**
     * @return string[]
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
}
