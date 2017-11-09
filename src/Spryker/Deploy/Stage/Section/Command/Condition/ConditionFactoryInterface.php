<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Stage\Section\Command\Condition;

interface ConditionFactoryInterface
{
    /**
     * @param array $condition
     *
     * @throws \Spryker\Deploy\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Deploy\Stage\Section\Command\Condition\ConditionInterface|\Spryker\Deploy\Stage\Section\Command\Condition\ExitCodeCondition
     */
    public function createCondition(array $condition);
}
