<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\CommandLine;

class CommandLineArgumentContainer
{
    /**
     * @var string|null
     */
    protected $storeNames;

    /**
     * @param string|null $storeNames
     */
    public function __construct(?string $storeNames = null)
    {
        $this->storeNames = $storeNames;
    }

    /**
     * @return string|null
     */
    public function getStoreNames(): ?string
    {
        return $this->storeNames;
    }
}
