<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner;

use Spryker\Setup\Configuration\ConfigurationFactory;
use Spryker\Setup\Executable\ExecutableFactory;
use Spryker\Setup\Runner\Environment\EnvironmentHelper;
use Spryker\Setup\Runner\Section\Command\CommandRunner;
use Spryker\Setup\Runner\Section\SectionRunner;

class RunnerFactory
{
    /**
     * @return \Spryker\Setup\Runner\SetupRunnerInterface
     */
    public function createSetupRunner()
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
    protected function createSectionRunner()
    {
        return new SectionRunner(
            $this->createCommandRunner()
        );
    }

    /**
     * @return \Spryker\Setup\Runner\Section\Command\CommandRunnerInterface
     */
    protected function createCommandRunner()
    {
        return new CommandRunner(
            $this->createExecutableFactory(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Setup\Executable\ExecutableFactory
     */
    protected function createExecutableFactory()
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface
     */
    protected function createEnvironmentHelper()
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
     */
    protected function createConfigurationBuilder()
    {
        return $this->createConfigurationFactory()->createConfigurationBuilder();
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationFactory
     */
    protected function createConfigurationFactory()
    {
        return new ConfigurationFactory();
    }
}
