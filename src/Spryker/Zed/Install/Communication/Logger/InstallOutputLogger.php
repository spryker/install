<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Logger;

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
    public function log($message): void
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
    protected function formatMessage($message): string
    {
        $formattedMessage = implode('', (array)$message);
        $formattedMessage = str_replace(PHP_EOL, '', $formattedMessage);
        $formattedMessage = strip_tags($formattedMessage);
        $formattedMessage = preg_replace([
            '/\\x1b[[][^A-Za-z]*[A-Za-z]/',
            '/[-=]{2,}/',
        ], '', $formattedMessage);

        return $formattedMessage !== null
            ? trim($formattedMessage, " \t\n\r\0\x0B-=")
            : (string)$formattedMessage;
    }
}
