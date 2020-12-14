<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\CommandLine;

class CommandLineArgumentContainer
{
    /**
     * @var string|null
     */
    protected $store;

    /**
     * @param string|null $store
     */
    public function __construct(?string $store = null)
    {
        $this->store = $store;
    }

    /**
     * @return string|null
     */
    public function getStore()
    {
        return $this->store;
    }
}
