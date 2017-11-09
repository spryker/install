<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Logger;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /**
     * @return \Spryker\Setup\Logger\SetupLoggerInterface
     */
    public function createOutputLogger(): SetupLoggerInterface
    {
        return new SetupOutputLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogger(): LoggerInterface
    {
        return new Logger('setup', [
            $this->createBufferedStreamHandler(),
        ]);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler(
            $this->createStreamHandler()
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function createStreamHandler(): HandlerInterface
    {
        $streamHandler = new StreamHandler(SPRYKER_ROOT . '/.spryker/setup/logs/' . date('Y-m-d-H:i:s') . '.log');

        return $streamHandler;
    }
}
