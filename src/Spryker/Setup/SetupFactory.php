<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\Configuration\Builder\ConfigurationBuilder;
use Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilder;
use Spryker\Setup\Configuration\Builder\Section\SectionBuilder;
use Spryker\Setup\Configuration\Configuration;
use Spryker\Setup\Configuration\Loader\ConfigurationLoader;
use Spryker\Setup\Configuration\Validator\ConfigurationValidator;
use Spryker\Setup\Executable\ExecutableFactory;
use Spryker\Setup\Logger\SetupOutputLogger;
use Spryker\Setup\Runner\Environment\EnvironmentHelper;
use Spryker\Setup\Runner\Section\Command\CommandRunner;
use Spryker\Setup\Runner\Section\SectionRunner;
use Spryker\Setup\Runner\SetupRunner;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Setup\Timer\Timer;

class SetupFactory
{
    /**
     * @return \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
     */
    public function createConfigurationBuilder()
    {
        return new ConfigurationBuilder(
            $this->createConfigurationLoader(),
            $this->createConfiguration(),
            $this->createSectionBuilder(),
            $this->createCommandBuilder()
        );
    }

    /**
     * @return \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected function createConfigurationLoader()
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected function createConfigurationValidator()
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected function createConfiguration()
    {
        return new Configuration();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected function createSectionBuilder()
    {
        return new SectionBuilder();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected function createCommandBuilder()
    {
        return new CommandBuilder(
            $this->createConditionFactory()
        );
    }

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
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    public function createConditionFactory()
    {
        return new ConditionFactory();
    }

    /**
     * @return \Spryker\Setup\Executable\ExecutableFactory
     */
    public function createExecutableFactory()
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface
     */
    public function createEnvironmentHelper()
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Setup\Timer\TimerInterface
     */
    public function createTimer()
    {
        return new Timer();
    }

    /**
     * @return \Spryker\Setup\Logger\SetupLoggerInterface
     */
    public function createOutputLogger()
    {
        return new SetupOutputLogger();
    }
}
