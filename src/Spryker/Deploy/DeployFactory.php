<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy;

use Spryker\Deploy\Logger\LoggerFactory;
use Spryker\Deploy\Logger\DeployLoggerInterface;
use Spryker\Deploy\Runner\RunnerFactory;
use Spryker\Deploy\Runner\DeployRunnerInterface;
use Spryker\Deploy\Timer\Timer;
use Spryker\Deploy\Timer\TimerInterface;

class DeployFactory
{
    /**
     * @return \Spryker\Deploy\Runner\DeployRunnerInterface
     */
    public function createDeployRunner(): DeployRunnerInterface
    {
        return $this->createRunnerFactory()->createDeployRunner();
    }

    /**
     * @return \Spryker\Deploy\Runner\RunnerFactory
     */
    protected function createRunnerFactory(): RunnerFactory
    {
        return new RunnerFactory();
    }

    /**
     * @return \Spryker\Deploy\Timer\TimerInterface
     */
    public function createTimer(): TimerInterface
    {
        return new Timer();
    }

    /**
     * @return \Spryker\Deploy\Logger\DeployLoggerInterface
     */
    public function createOutputLogger(): DeployLoggerInterface
    {
        return $this->createLoggerFactory()->createOutputLogger();
    }

    /**
     * @return \Spryker\Deploy\Logger\LoggerFactory
     */
    protected function createLoggerFactory(): LoggerFactory
    {
        return new LoggerFactory();
    }
}
