<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\CommandLine;

class CommandLineArgumentContainer
{
    /**
     * @var string
     */
    protected $stage;

    /**
     * @var string
     */
    protected $store;

    /**
     * @param string $stage
     * @param string $store
     */
    public function __construct($stage, $store)
    {
        $this->stage = $stage;
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }
}
