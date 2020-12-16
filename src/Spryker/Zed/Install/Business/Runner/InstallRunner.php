<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner;

use Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;

class InstallRunner implements InstallRunnerInterface
{
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
     * @param \Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface $sectionRunner
     * @param \Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface $configurationBuilder
     * @param \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface $environmentHelper
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
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return void
     */
    public function run(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): void {
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
}
