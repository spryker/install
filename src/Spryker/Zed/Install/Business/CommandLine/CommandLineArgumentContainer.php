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
    protected $storeName;

    /**
     * @param string|null $storeName
     */
    public function __construct(?string $storeName = null)
    {
        $this->storeName = $storeName;
    }

    /**
     * @return string|null
     */
    public function getStoreName(): ?string
    {
        return $this->storeName;
    }
}
