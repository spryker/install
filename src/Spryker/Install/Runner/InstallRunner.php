<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Runner;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Install\Runner\Section\SectionRunnerInterface;
use Spryker\Style\StyleInterface;

class InstallRunner implements InstallRunnerInterface
{
    /**
     * @var \Spryker\Install\Runner\Section\SectionRunnerInterface
     */
    protected $sectionRunner;

    /**
     * @var \Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @var \Spryker\Install\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @param \Spryker\Install\Runner\Section\SectionRunnerInterface $sectionRunner
     * @param \Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface $configurationBuilder
     * @param \Spryker\Install\Runner\Environment\EnvironmentHelperInterface $environmentHelper
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
     * @param \Spryker\Install\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Install\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
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

        $output->endInstall($configuration->getStage());
    }

    /**
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function executeStage(ConfigurationInterface $configuration)
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
}
