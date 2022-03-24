<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Console;

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
    /**
     * @var string
     */
    public const ARGUMENT_ENVIRONMENT = 'environment';

    /**
     * @var string
     */
    public const ARGUMENT_STORE = 'store';

    /**
     * @var string
     */
    public const OPTION_RECIPE = 'recipe';

    /**
     * @var string
     */
    public const OPTION_RECIPE_SHORT = 'r';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN_SHORT = 'd';

    /**
     * @var string
     */
    public const OPTION_SECTIONS = 'sections';

    /**
     * @var string
     */
    public const OPTION_SECTIONS_SHORT = 's';

    /**
     * @var string
     */
    public const OPTION_GROUPS = 'groups';

    /**
     * @var string
     */
    public const OPTION_GROUPS_SHORT = 'g';

    /**
     * @var string
     */
    public const OPTION_EXCLUDE = 'exclude';

    /**
     * @var string
     */
    public const OPTION_EXCLUDE_SHORT = 'x';

    /**
     * @var string
     */
    public const OPTION_INCLUDE_EXCLUDED = 'include-excluded';

    /**
     * @var string
     */
    public const OPTION_INCLUDE_EXCLUDED_SHORT = 'e';

    /**
     * @var string
     */
    public const OPTION_INTERACTIVE = 'interactive';

    /**
     * @var string
     */
    public const OPTION_INTERACTIVE_SHORT = 'i';

    /**
     * @var string
     */
    public const OPTION_BREAKPOINT = 'breakpoint';

    /**
     * @var string
     */
    public const OPTION_BREAKPOINT_SHORT = 'b';

    /**
     * @var string
     */
    public const OPTION_LOG = 'log';

    /**
     * @var string
     */
    public const OPTION_LOG_SHORT = 'l';

    /**
     * @var string
     */
    public const OPTION_ASK_BEFORE_CONTINUE = 'ask-before-continue';

    /**
     * @var string
     */
    public const OPTION_ASK_BEFORE_CONTINUE_SHORT = 'a';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $environment = $this->getFactory()->getConfig()->getEnvironment();
        $this->setName('install')
            ->setDescription('Run install for a specified environment.')
            ->addArgument(static::ARGUMENT_STORE, InputArgument::OPTIONAL, 'Name of the store(s) for which the install should be executed (multiple comma separated values allowed, e.g single - DE or multiple - DE,AT)')
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
        $this->getFacade()->runInstall($input, $output);

        return static::CODE_SUCCESS;
    }
}
