<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Runner;

use Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Install\Configuration\ConfigurationFactory;
use Spryker\Install\Executable\ExecutableFactory;
use Spryker\Install\Runner\Environment\EnvironmentHelper;
use Spryker\Install\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Install\Runner\Section\Command\CommandRunner;
use Spryker\Install\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Install\Runner\Section\SectionRunner;
use Spryker\Install\Runner\Section\SectionRunnerInterface;

class RunnerFactory
{
    /**
     * @return \Spryker\Install\Runner\InstallRunnerInterface
     */
    public function createInstallRunner(): InstallRunnerInterface
    {
        return new InstallRunner(
            $this->createSectionRunner(),
            $this->createConfigurationBuilder(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Install\Runner\Section\SectionRunnerInterface
     */
    protected function createSectionRunner(): SectionRunnerInterface
    {
        return new SectionRunner(
            $this->createCommandRunner()
        );
    }

    /**
     * @return \Spryker\Install\Runner\Section\Command\CommandRunnerInterface
     */
    protected function createCommandRunner(): CommandRunnerInterface
    {
        return new CommandRunner(
            $this->createExecutableFactory(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Install\Executable\ExecutableFactory
     */
    protected function createExecutableFactory(): ExecutableFactory
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Install\Runner\Environment\EnvironmentHelperInterface
     */
    protected function createEnvironmentHelper(): EnvironmentHelperInterface
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected function createConfigurationBuilder(): ConfigurationBuilderInterface
    {
        return $this->createConfigurationFactory()->createConfigurationBuilder();
    }

    /**
     * @return \Spryker\Install\Configuration\ConfigurationFactory
     */
    protected function createConfigurationFactory(): ConfigurationFactory
    {
        return new ConfigurationFactory();
    }
}
