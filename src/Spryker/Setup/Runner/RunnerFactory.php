<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner;

use Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Setup\Configuration\ConfigurationFactory;
use Spryker\Setup\Executable\ExecutableFactory;
use Spryker\Setup\Runner\Environment\EnvironmentHelper;
use Spryker\Setup\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Setup\Runner\Section\Command\CommandRunner;
use Spryker\Setup\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Setup\Runner\Section\SectionRunner;
use Spryker\Setup\Runner\Section\SectionRunnerInterface;

class RunnerFactory
{
    /**
     * @return \Spryker\Setup\Runner\SetupRunnerInterface
     */
    public function createSetupRunner(): SetupRunnerInterface
    {
        return new SetupRunner(
            $this->createSectionRunner(),
            $this->createConfigurationBuilder(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Setup\Runner\Section\SectionRunnerInterface
     */
    protected function createSectionRunner(): SectionRunnerInterface
    {
        return new SectionRunner(
            $this->createCommandRunner()
        );
    }

    /**
     * @return \Spryker\Setup\Runner\Section\Command\CommandRunnerInterface
     */
    protected function createCommandRunner(): CommandRunnerInterface
    {
        return new CommandRunner(
            $this->createExecutableFactory(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Setup\Executable\ExecutableFactory
     */
    protected function createExecutableFactory(): ExecutableFactory
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface
     */
    protected function createEnvironmentHelper(): EnvironmentHelperInterface
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected function createConfigurationBuilder(): ConfigurationBuilderInterface
    {
        return $this->createConfigurationFactory()->createConfigurationBuilder();
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationFactory
     */
    protected function createConfigurationFactory(): ConfigurationFactory
    {
        return new ConfigurationFactory();
    }
}
