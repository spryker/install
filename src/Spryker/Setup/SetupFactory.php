<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\Logger\LoggerFactory;
use Spryker\Setup\Runner\RunnerFactory;
use Spryker\Setup\Timer\Timer;

class SetupFactory
{
    /**
     * @return \Spryker\Setup\Runner\SetupRunnerInterface
     */
    public function createSetupRunner()
    {
        return $this->createRunnerFactory()->createSetupRunner();
    }

    /**
     * @return \Spryker\Setup\Runner\RunnerFactory
     */
    protected function createRunnerFactory()
    {
        return new RunnerFactory();
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
        return $this->createdLoggerFactory()->createOutputLogger();
    }

    /**
     * @return \Spryker\Setup\Logger\LoggerFactory
     */
    protected function createdLoggerFactory()
    {
        return new LoggerFactory();
    }
}
