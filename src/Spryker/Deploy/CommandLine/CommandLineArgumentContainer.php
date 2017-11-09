<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\CommandLine;

class CommandLineArgumentContainer
{
    /**
     * @var string
     */
    protected $stage;

    /**
     * @var string|null
     */
    protected $store;

    /**
     * @param string $stage
     * @param string|null $store
     */
    public function __construct(string $stage, string $store = null)
    {
        $this->stage = $stage;
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @return string|null
     */
    public function getStore()
    {
        return $this->store;
    }
}
