<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Logger;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /**
     * @return \Spryker\Install\Logger\InstallLoggerInterface
     */
    public function createOutputLogger(): InstallLoggerInterface
    {
        return new InstallOutputLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogger(): LoggerInterface
    {
        return new Logger('install', [
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
        $streamHandler = new StreamHandler(SPRYKER_ROOT . '/data/install/logs/' . date('Y-m-d_H-i-s') . '.log');

        return $streamHandler;
    }
}
