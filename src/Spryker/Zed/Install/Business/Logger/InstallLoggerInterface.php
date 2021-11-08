<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Logger;

interface InstallLoggerInterface
{
    /**
     * @param array|string $message
     *
     * @return void
     */
    public function log($message): void;
}
