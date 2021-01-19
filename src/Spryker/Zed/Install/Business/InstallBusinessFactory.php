<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilder;
use Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilder;
use Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilder;
use Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\Configuration;
use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoader;
use Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidator;
use Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidatorInterface;
use Spryker\Zed\Install\Business\Executable\ExecutableFactory;
use Spryker\Zed\Install\Business\Executable\ExecutableFactoryInterface;
use Spryker\Zed\Install\Business\Logger\InstallLoggerInterface;
use Spryker\Zed\Install\Business\Logger\InstallOutputLogger;
use Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelper;
use Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Zed\Install\Business\Runner\InstallRunner;
use Spryker\Zed\Install\Business\Runner\InstallRunnerInterface;
use Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunner;
use Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Zed\Install\Business\Runner\Section\SectionRunner;
use Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionFactoryInterface;
use Spryker\Zed\Install\Business\Style\Builder\StyleBuilder;
use Spryker\Zed\Install\Business\Style\Builder\StyleBuilderInterface;
use Spryker\Zed\Install\Business\Timer\Timer;
use Spryker\Zed\Install\Business\Timer\TimerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Install\InstallConfig getConfig()
 */
class InstallBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Install\Business\Runner\InstallRunnerInterface
     */
    public function createInstallRunner(): InstallRunnerInterface
    {
        return new InstallRunner(
            $this->createSectionRunner(),
            $this->createConfigurationBuilder(),
            $this->createEnvironmentHelper(),
            $this->createStyleBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Runner\Section\SectionRunnerInterface
     */
    public function createSectionRunner(): SectionRunnerInterface
    {
        return new SectionRunner(
            $this->createCommandRunner()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunnerInterface
     */
    public function createCommandRunner(): CommandRunnerInterface
    {
        return new CommandRunner(
            $this->createExecutableFactory(),
            $this->createEnvironmentHelper()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Executable\ExecutableFactoryInterface
     */
    public function createExecutableFactory(): ExecutableFactoryInterface
    {
        return new ExecutableFactory();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface
     */
    public function createEnvironmentHelper(): EnvironmentHelperInterface
    {
        return new EnvironmentHelper();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Builder\ConfigurationBuilderInterface
     */
    public function createConfigurationBuilder(): ConfigurationBuilderInterface
    {
        return new ConfigurationBuilder(
            $this->createConfigurationLoader(),
            $this->createConfiguration(),
            $this->createSectionBuilder(),
            $this->createCommandBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoaderInterface
     */
    public function createConfigurationLoader(): ConfigurationLoaderInterface
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidatorInterface
     */
    public function createConfigurationValidator(): ConfigurationValidatorInterface
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function createConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilderInterface
     */
    public function createSectionBuilder(): SectionBuilderInterface
    {
        return new SectionBuilder();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    public function createCommandBuilder(): CommandBuilderInterface
    {
        return new CommandBuilder(
            $this->createConditionFactory()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    public function createConditionFactory(): ConditionFactoryInterface
    {
        return new ConditionFactory();
    }

    /**
     * @return \Spryker\Zed\Install\Business\Style\Builder\StyleBuilderInterface
     */
    public function createStyleBuilder(): StyleBuilderInterface
    {
        return new StyleBuilder(
            $this->createTimer(),
            $this->createOutputLogger()
        );
    }

    /**
     * @return \Spryker\Zed\Install\Business\Logger\InstallLoggerInterface
     */
    public function createOutputLogger(): InstallLoggerInterface
    {
        return new InstallOutputLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger(): LoggerInterface
    {
        return new Logger('install', [
            $this->createBufferedStreamHandler(),
        ]);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler(
            $this->createStreamHandler()
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createStreamHandler(): HandlerInterface
    {
        return new StreamHandler($this->getConfig()->getLogFilePath());
    }

    /**
     * @return \Spryker\Zed\Install\Business\Timer\TimerInterface
     */
    public function createTimer(): TimerInterface
    {
        return new Timer();
    }
}
