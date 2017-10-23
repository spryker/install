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
     * @param string $stage
     */
    public function __construct($stage)
    {
        $this->stage = $stage;
    }

    /**
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }
}
