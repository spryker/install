<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner;

use Spryker\Deploy\CommandLine\CommandLineArgumentContainer;
use Spryker\Deploy\CommandLine\CommandLineOptionContainer;
use Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Deploy\Runner\Section\SectionRunnerInterface;
use Spryker\Style\StyleInterface;

class DeployRunner implements DeployRunnerInterface
{
    /**
     * @var \Spryker\Deploy\Runner\Section\SectionRunnerInterface
     */
    protected $sectionRunner;

    /**
     * @var \Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @var \Spryker\Deploy\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @param \Spryker\Deploy\Runner\Section\SectionRunnerInterface $sectionRunner
     * @param \Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface $configurationBuilder
     * @param \Spryker\Deploy\Runner\Environment\EnvironmentHelperInterface $environmentHelper
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
     * @param \Spryker\Deploy\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Deploy\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
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

        $output->endDeploy($configuration->getStage());
    }

    /**
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function executeStage(ConfigurationInterface $configuration)
    {
        $stage = $configuration->getStage();
        $configuration->getOutput()->startDeploy($stage);

        foreach ($stage->getSections() as $section) {
            if ($section->isExcluded()) {
                continue;
            }

            $this->sectionRunner->run($section, $configuration);
        }
    }
}
