<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command\Condition;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @param string $command
     * @param int $exitCode
     */
    public function __construct($command, $exitCode)
    {
        $this->command = $command;
        $this->exitCode = $exitCode;
    }
}
