<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Logger;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /**
     * @return \Spryker\Deploy\Logger\DeployLoggerInterface
     */
    public function createOutputLogger(): DeployLoggerInterface
    {
        return new DeployOutputLogger($this->createLogger());
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function createLogger(): LoggerInterface
    {
        return new Logger('deploy', [
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
        $streamHandler = new StreamHandler(SPRYKER_ROOT . '/data/deploy/logs/' . date('Y-m-d-H:i:s') . '.log');

        return $streamHandler;
    }
}
