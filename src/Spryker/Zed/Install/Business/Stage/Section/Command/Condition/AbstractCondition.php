<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage\Section\Command\Condition;

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
    public function __construct(string $command, int $exitCode)
    {
        $this->command = $command;
        $this->exitCode = $exitCode;
    }
}
