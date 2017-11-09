<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Logger;

interface DeployLoggerInterface
{
    /**
     * @param string|array $message
     *
     * @return void
     */
    public function log($message);
}
