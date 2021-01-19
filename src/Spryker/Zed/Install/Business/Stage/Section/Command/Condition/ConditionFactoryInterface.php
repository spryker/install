<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage\Section\Command\Condition;

interface ConditionFactoryInterface
{
    /**
     * @param array $condition
     *
     * @throws \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionInterface|\Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ExitCodeCondition
     */
    public function createCondition(array $condition);
}
