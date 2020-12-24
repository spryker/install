<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner;

use Spryker\Zed\Install\Business\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Business\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Exception\InstallException;
use Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface;
use Spryker\Zed\Install\Business\Style\Builder\StyleBuilderInterface;
use Spryker\Zed\Install\Business\Style\StyleInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallRunner implements InstallRunnerInterface
{
    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const ARGUMENT_STORE = 'store';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::OPTION_RECIPE
     */
    protected const OPTION_RECIPE = 'recipe';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_DRY_RUN = 'dry-run';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_SECTIONS = 'sections';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_GROUPS = 'groups';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_EXCLUDE = 'exclude';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_INCLUDE_EXCLUDED = 'include-excluded';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_INTERACTIVE = 'interactive';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_BREAKPOINT = 'breakpoint';

    /**
     * @uses \Spryker\Zed\Install\Communication\Console\InstallConsole::ARGUMENT_STORE
     */
    protected const OPTION_ASK_BEFORE_CONTINUE = 'ask-before-continue';

    /**
     * @var \Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface
     */
    protected $sectionRunner;

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @var \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @var \Spryker\Zed\Install\Business\Style\Builder\StyleBuilderInterface
     */
    protected $styleBuilder;

    /**
     * @param \Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface $sectionRunner
     * @param \Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface $configurationBuilder
     * @param \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface $environmentHelper
     * @param \Spryker\Zed\Install\Business\Style\Builder\StyleBuilderInterface $styleBuilder
     */
    public function __construct(
        SectionRunnerInterface $sectionRunner,
        ConfigurationBuilderInterface $configurationBuilder,
        EnvironmentHelperInterface $environmentHelper,
        StyleBuilderInterface $styleBuilder
    ) {
        $this->sectionRunner = $sectionRunner;
        $this->environmentHelper = $environmentHelper;
        $this->configurationBuilder = $configurationBuilder;
        $this->styleBuilder = $styleBuilder;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function run(InputInterface $input, OutputInterface $output): void
    {
        $configuration = $this->configurationBuilder->buildConfiguration(
            $this->buildCommandLineArgumentContainer($input),
            $this->buildCommandLineOptionContainer($input),
            $this->styleBuilder->buildStyle($input, $output)
        );

        $this->startInstall($configuration);
        $this->executeStage($configuration);
        $this->endInstall($configuration);
    }

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function startInstall(ConfigurationInterface $configuration): void
    {
        $this->environmentHelper->putEnv('FORCE_COLOR_MODE', true);
        $this->environmentHelper->putEnvs($configuration->getEnv());
        $this->printStartNotes($configuration);
    }

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function printStartNotes(ConfigurationInterface $configuration): void
    {
        if (count($configuration->getSections())) {
            $this->printOptionNote(
                $configuration->getOutput(),
                'Install will only run this section(s) "%s"',
                $configuration->getSections()
            );
        }

        if (count($configuration->getGroups())) {
            $this->printOptionNote(
                $configuration->getOutput(),
                'Install will only run this group(s) "%s"',
                $configuration->getGroups()
            );
        }

        if (count($configuration->getExcluded())) {
            $this->printOptionNote(
                $configuration->getOutput(),
                'Install will exclude this group(s) or section(s) "%s"',
                $configuration->getExcluded()
            );
        }

        if (count($configuration->getIncludeExcluded())) {
            $this->printOptionNote(
                $configuration->getOutput(),
                'Install will include this excluded section(s) or command(s) "%s"',
                $configuration->getIncludeExcluded()
            );
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Style\StyleInterface $output
     * @param string $notePatters
     * @param array $option
     *
     * @return void
     */
    protected function printOptionNote(StyleInterface $output, string $notePatters, array $option): void
    {
        $output->note(sprintf($notePatters, implode(', ', $option)));
    }

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function executeStage(ConfigurationInterface $configuration): void
    {
        $stage = $configuration->getStage();
        $configuration->getOutput()->startInstall($stage);

        foreach ($stage->getSections() as $section) {
            if ($section->isExcluded()) {
                continue;
            }

            $this->sectionRunner->run($section, $configuration);
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function endInstall(ConfigurationInterface $configuration): void
    {
        $configuration->getOutput()->endInstall(
            $configuration->getStage()
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \Spryker\Zed\Install\Business\Exception\InstallException
     *
     * @return \Spryker\Zed\Install\Business\CommandLine\CommandLineArgumentContainer
     */
    protected function buildCommandLineArgumentContainer(InputInterface $input): CommandLineArgumentContainer
    {
        $store = $input->getArgument(static::ARGUMENT_STORE);

        if ($store !== null && !is_string($store)) {
            throw new InstallException(
                sprintf(
                    'Value of `%s` argument should return `string|null` type. Return type is `%s`.',
                    static::ARGUMENT_STORE,
                    gettype($store)
                )
            );
        }

        return new CommandLineArgumentContainer($store);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \Spryker\Zed\Install\Business\Exception\InstallException
     *
     * @return \Spryker\Zed\Install\Business\CommandLine\CommandLineOptionContainer
     */
    protected function buildCommandLineOptionContainer(InputInterface $input): CommandLineOptionContainer
    {
        $recipeOption = $input->getOption(static::OPTION_RECIPE);

        if (!is_string($recipeOption)) {
            throw new InstallException(
                sprintf(
                    'Value of `%s` option should return `string` type. Return `%s`.',
                    static::OPTION_RECIPE,
                    gettype($recipeOption)
                )
            );
        }

        return new CommandLineOptionContainer(
            $recipeOption,
            (array)$input->getOption(static::OPTION_SECTIONS),
            (array)$input->getOption(static::OPTION_GROUPS),
            (array)$input->getOption(static::OPTION_EXCLUDE),
            (array)$input->getOption(static::OPTION_INCLUDE_EXCLUDED),
            (bool)$input->getOption(static::OPTION_INTERACTIVE),
            (bool)$input->getOption(static::OPTION_DRY_RUN),
            (bool)$input->getOption(static::OPTION_BREAKPOINT),
            (bool)$input->getOption(static::OPTION_ASK_BEFORE_CONTINUE)
        );
    }
}
