<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Loader;

interface ConfigurationLoaderInterface
{
    /**
     * @param string $stageName
     *
     * @return array
     */
    public function loadConfiguration(string $stageName): array;
}
