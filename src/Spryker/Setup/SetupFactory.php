<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Setup\Logger\SetupOutputLogger;
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
        return new SetupOutputLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogger()
    {
        return new Logger('setup', [
            $this->createBufferedStreamHandler(),
        ]);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createBufferedStreamHandler()
    {
        return new BufferHandler(
            $this->createStreamHandler()
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createStreamHandler()
    {
        $streamHandler = new StreamHandler('/data/shop/development/current/.spryker/setup/logs/' . date('Y-m-d-H:i:s') . '.log');

        return $streamHandler;
    }
}
