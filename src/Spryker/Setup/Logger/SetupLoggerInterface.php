<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Logger;

interface SetupLoggerInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function log($message);
}
