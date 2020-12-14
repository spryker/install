<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Logger;

interface InstallLoggerInterface
{
    /**
     * @param string|array $message
     *
     * @return void
     */
    public function log($message);
}
