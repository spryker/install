<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Loader;

interface ConfigurationLoaderInterface
{
    /**
     * @param string $recipe
     *
     * @return array
     */
    public function loadConfiguration(string $recipe): array;
}
