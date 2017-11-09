<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command\Condition;

use Spryker\Setup\Stage\Section\Command\Condition\Exception\ConditionNotFoundException;

class ConditionFactory implements ConditionFactoryInterface
{
    /**
     * @var array
     */
    protected $conditionNameToConditionClassMap = [
        'ifExitCode' => ExitCodeCondition::class,
        'notExitCode' => NotExitCodeCondition::class,
    ];

    /**
     * @param array $condition
     *
     * @throws \Spryker\Setup\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface
     */
    public function createCondition(array $condition): ConditionInterface
    {
        foreach ($this->conditionNameToConditionClassMap as $conditionName => $conditionClass) {
            if (isset($condition[$conditionName])) {
                return new $conditionClass($condition['command'], $condition[$conditionName]);
            }
        }

        throw new ConditionNotFoundException(sprintf('Condition could not be found, available conditions are: %s', implode(', ', $this->conditionNameToConditionClassMap)));
    }
}
