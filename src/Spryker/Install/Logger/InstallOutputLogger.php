<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Logger;

use Psr\Log\LoggerInterface;

class InstallOutputLogger implements InstallLoggerInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string|array $message
     *
     * @return void
     */
    public function log($message)
    {
        $formattedMessage = $this->formatMessage($message);
        if (!empty($formattedMessage)) {
            $this->logger->info($formattedMessage);
        }
    }

    /**
     * @param string|array $message
     *
     * @return string
     */
    private function formatMessage($message)
    {
        $message = implode('', (array)$message);
        $message = str_replace(PHP_EOL, '', $message);
        $message = strip_tags($message);
        $message = preg_replace('/\\x1b[[][^A-Za-z]*[A-Za-z]/', '', $message);
        $message = preg_replace('/[-=]{2,}/', '', $message);
        $message = trim($message, " \t\n\r\0\x0B-=");

        return $message;
    }
}
