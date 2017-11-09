<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\Logger\LoggerFactory;
use Spryker\Setup\Logger\SetupLoggerInterface;
use Spryker\Setup\Runner\RunnerFactory;
use Spryker\Setup\Runner\SetupRunnerInterface;
use Spryker\Setup\Timer\Timer;
use Spryker\Setup\Timer\TimerInterface;

class SetupFactory
{
    /**
     * @return \Spryker\Setup\Runner\SetupRunnerInterface
     */
    public function createSetupRunner(): SetupRunnerInterface
    {
        return $this->createRunnerFactory()->createSetupRunner();
    }

    /**
     * @return \Spryker\Setup\Runner\RunnerFactory
     */
    protected function createRunnerFactory(): RunnerFactory
    {
        return new RunnerFactory();
    }

    /**
     * @return \Spryker\Setup\Timer\TimerInterface
     */
    public function createTimer(): TimerInterface
    {
        return new Timer();
    }

    /**
     * @return \Spryker\Setup\Logger\SetupLoggerInterface
     */
    public function createOutputLogger(): SetupLoggerInterface
    {
        return $this->createdLoggerFactory()->createOutputLogger();
    }

    /**
     * @return \Spryker\Setup\Logger\LoggerFactory
     */
    protected function createdLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory();
    }
}
