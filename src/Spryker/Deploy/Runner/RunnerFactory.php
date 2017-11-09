<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner;

use Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Deploy\Configuration\ConfigurationFactory;
use Spryker\Deploy\Executable\ExecutableFactory;
use Spryker\Deploy\Runner\Environment\EnvironmentHelper;
use Spryker\Deploy\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Deploy\Runner\Section\Command\CommandRunner;
use Spryker\Deploy\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Deploy\Runner\Section\SectionRunner;
use Spryker\Deploy\Runner\Section\SectionRunnerInterface;

class RunnerFactory
{
    /**
     * @return \Spryker\Deploy\Runner\DeployRunnerInterface
     */
    public function createDeployRunner(): DeployRunnerInterface
    {
        return new DeployRunner(
            $this->createSectionRunner(),
            $this->createConfigurationBuilder(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Deploy\Runner\Section\SectionRunnerInterface
     */
    protected function createSectionRunner(): SectionRunnerInterface
    {
        return new SectionRunner(
            $this->createCommandRunner()
        );
    }

    /**
     * @return \Spryker\Deploy\Runner\Section\Command\CommandRunnerInterface
     */
    protected function createCommandRunner(): CommandRunnerInterface
    {
        return new CommandRunner(
            $this->createExecutableFactory(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Deploy\Executable\ExecutableFactory
     */
    protected function createExecutableFactory(): ExecutableFactory
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Deploy\Runner\Environment\EnvironmentHelperInterface
     */
    protected function createEnvironmentHelper(): EnvironmentHelperInterface
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected function createConfigurationBuilder(): ConfigurationBuilderInterface
    {
        return $this->createConfigurationFactory()->createConfigurationBuilder();
    }

    /**
     * @return \Spryker\Deploy\Configuration\ConfigurationFactory
     */
    protected function createConfigurationFactory(): ConfigurationFactory
    {
        return new ConfigurationFactory();
    }
}
