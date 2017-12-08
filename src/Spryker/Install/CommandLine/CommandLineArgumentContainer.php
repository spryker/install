<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\CommandLine;

class CommandLineArgumentContainer
{
    /**
     * @var string|null
     */
    protected $store;

    /**
     * @param string|null $store
     */
    public function __construct(string $store = null)
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
