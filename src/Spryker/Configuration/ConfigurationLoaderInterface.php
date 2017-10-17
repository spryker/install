<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration;

interface ConfigurationLoaderInterface
{
    /**
     * @return array
     */
    public function loadConfiguration();
}
