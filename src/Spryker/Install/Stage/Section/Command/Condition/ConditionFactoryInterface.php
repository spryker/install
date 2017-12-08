<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
