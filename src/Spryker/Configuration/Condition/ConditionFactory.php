<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Condition;

use Spryker\Configuration\Exception\ConfigurationException;

class ConditionFactory
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
     * @throws \Spryker\Configuration\Exception\ConfigurationException
     *
     * @return \Spryker\Configuration\Condition\ConditionInterface|\Spryker\Configuration\Condition\ExitCodeCondition
     */
    public function createCondition(array $condition)
    {
        foreach ($this->conditionNameToConditionClassMap as $conditionName => $conditionClass) {
            if (isset($condition[$conditionName])) {
                return new $conditionClass($condition['command'], $condition[$conditionName]);
            }
        }

        throw new ConfigurationException(sprintf('Condition could not be found, available conditions are: ', implode(', ', $this->conditionNameToConditionClassMap)));
    }
}
