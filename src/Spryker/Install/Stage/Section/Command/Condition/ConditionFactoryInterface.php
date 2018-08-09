<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Stage\Section\Command\Condition;

interface ConditionFactoryInterface
{
    /**
     * @param array $condition
     *
     * @throws \Spryker\Install\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface|\Spryker\Install\Stage\Section\Command\Condition\ExitCodeCondition
     */
    public function createCondition(array $condition);
}
