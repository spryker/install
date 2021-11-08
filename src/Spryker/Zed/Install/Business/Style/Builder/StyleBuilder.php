<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Style\Builder;

use Spryker\Zed\Install\Business\Logger\InstallLoggerInterface;
use Spryker\Zed\Install\Business\Style\SprykerStyle;
use Spryker\Zed\Install\Business\Style\StyleInterface;
use Spryker\Zed\Install\Business\Timer\TimerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StyleBuilder implements StyleBuilderInterface
{
    /**
     * @var string
     */
    protected const OPTION_LOG = 'log';

    /**
     * @var \Spryker\Zed\Install\Business\Timer\TimerInterface
     */
    protected $timer;

    /**
     * @var \Spryker\Zed\Install\Business\Logger\InstallLoggerInterface|null
     */
    protected $logger;

    /**
     * @param \Spryker\Zed\Install\Business\Timer\TimerInterface $timer
     * @param \Spryker\Zed\Install\Business\Logger\InstallLoggerInterface|null $logger
     */
    public function __construct(TimerInterface $timer, ?InstallLoggerInterface $logger)
    {
        $this->timer = $timer;
        $this->logger = $logger;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    public function buildStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        if ($input->getOption(static::OPTION_LOG)) {
            return $this->createLoggableStyle($input, $output);
        }

        return $this->createStyle($input, $output);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    public function createStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        return new SprykerStyle(
            $input,
            $output,
            $this->timer,
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    public function createLoggableStyle(InputInterface $input, OutputInterface $output): StyleInterface
    {
        return new SprykerStyle(
            $input,
            $output,
            $this->timer,
            $this->logger,
        );
    }
}
