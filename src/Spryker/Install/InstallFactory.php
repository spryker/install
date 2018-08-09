<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install;

use Spryker\Install\Logger\InstallLoggerInterface;
use Spryker\Install\Logger\LoggerFactory;
use Spryker\Install\Runner\InstallRunnerInterface;
use Spryker\Install\Runner\RunnerFactory;
use Spryker\Install\Timer\Timer;
use Spryker\Install\Timer\TimerInterface;

class InstallFactory
{
    /**
     * @return \Spryker\Install\Runner\InstallRunnerInterface
     */
    public function createInstallRunner(): InstallRunnerInterface
    {
        return $this->createRunnerFactory()->createInstallRunner();
    }

    /**
     * @return \Spryker\Install\Runner\RunnerFactory
     */
    protected function createRunnerFactory(): RunnerFactory
    {
        return new RunnerFactory();
    }

    /**
     * @return \Spryker\Install\Timer\TimerInterface
     */
    public function createTimer(): TimerInterface
    {
        return new Timer();
    }

    /**
     * @return \Spryker\Install\Logger\InstallLoggerInterface
     */
    public function createOutputLogger(): InstallLoggerInterface
    {
        return $this->createLoggerFactory()->createOutputLogger();
    }

    /**
     * @return \Spryker\Install\Logger\LoggerFactory
     */
    protected function createLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory();
    }
}
