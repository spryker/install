<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Install\Communication\Logger\InstallLoggerInterface;
use Spryker\Zed\Install\Communication\Logger\InstallOutputLogger;
use Spryker\Zed\Install\Communication\Style\SprykerStyle;
use Spryker\Zed\Install\Communication\Style\StyleInterface;
use Spryker\Zed\Install\Communication\Timer\Timer;
use Spryker\Zed\Install\Communication\Timer\TimerInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Install\InstallConfig getConfig()
 * @method \Spryker\Zed\Install\Business\InstallFacadeInterface getFacade()
 */
class InstallCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Install\Communication\Logger\InstallLoggerInterface
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
        $streamHandler = new StreamHandler($this->getConfig()->getLogFilePath());

        return $streamHandler;
    }

    /**
     * @return \Spryker\Zed\Install\Communication\Timer\TimerInterface
     */
    public function createTimer(): TimerInterface
    {
        return new Timer();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Communication\Style\StyleInterface
     */
    public function createStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        return new SprykerStyle(
            $input,
            $output,
            $this->createTimer()
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Communication\Style\StyleInterface
     */
    public function createLoggableStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        return new SprykerStyle(
            $input,
            $output,
            $this->createTimer(),
            $this->createOutputLogger()
        );
    }
}
