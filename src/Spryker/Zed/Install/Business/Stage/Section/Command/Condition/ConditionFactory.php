<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage\Section\Command\Condition;

use Spryker\Zed\Install\Business\Stage\Section\Command\Condition\Exception\ConditionNotFoundException;

class ConditionFactory implements ConditionFactoryInterface
{
    /**
     * @var string
     */
    public const CONDITION_NAME_IF_EXIT_CODE = 'ifExitCode';

    /**
     * @var string
     */
    public const CONDITION_NAME_NOT_EXIT_CODE = 'notExitCode';

    /**
     * @var array
     */
    protected $conditionNameToConditionClassMap = [
        self::CONDITION_NAME_IF_EXIT_CODE => ExitCodeCondition::class,
        self::CONDITION_NAME_NOT_EXIT_CODE => NotExitCodeCondition::class,
    ];

    /**
     * @param array $condition
     *
     * @throws \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionInterface
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
