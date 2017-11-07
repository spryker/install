<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Setup\Runner\Section\SectionRunnerInterface;
use Spryker\Style\StyleInterface;

class SetupRunner implements SetupRunnerInterface
{
    /**
     * @var \Spryker\Setup\Runner\Section\SectionRunnerInterface
     */
    protected $sectionRunner;

    /**
     * @var \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @var \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @param \Spryker\Setup\Runner\Section\SectionRunnerInterface $sectionRunner
     * @param \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface $configurationBuilder
     * @param \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface $environmentHelper
     */
    public function __construct(
        SectionRunnerInterface $sectionRunner,
        ConfigurationBuilderInterface $configurationBuilder,
        EnvironmentHelperInterface $environmentHelper
    ) {
        $this->sectionRunner = $sectionRunner;
        $this->environmentHelper = $environmentHelper;
        $this->configurationBuilder = $configurationBuilder;
    }

    /**
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return void
     */
    public function run(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ) {
        $configuration = $this->configurationBuilder->buildConfiguration(
            $commandLineArgumentContainer,
            $commandLineOptionContainer,
            $output
        );

        $this->environmentHelper->putEnv('FORCE_COLOR_MODE', true);
        $this->environmentHelper->putEnvs($configuration->getEnv());

        $this->executeStage($configuration);

        $output->endSetup($configuration->getStage());
    }

    /**
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function executeStage(ConfigurationInterface $configuration)
    {
        $stage = $configuration->getStage();
        $configuration->getOutput()->startSetup($stage);

        foreach ($stage->getSections() as $section) {
            if ($section->isExcluded()) {
                continue;
            }

            $this->sectionRunner->run($section, $configuration);
        }
    }
}
