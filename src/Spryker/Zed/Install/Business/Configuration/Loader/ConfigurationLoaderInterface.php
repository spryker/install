<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Loader;

interface ConfigurationLoaderInterface
{
    /**
     * @param string $recipe
     *
     * @return array
     */
    public function loadConfiguration(string $recipe): array;
}
