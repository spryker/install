<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command\Condition;

interface ConditionFactoryInterface
{
    /**
     * @param array $condition
     *
     * @throws \Spryker\Setup\Stage\Section\Command\Condition\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface|\Spryker\Setup\Stage\Section\Command\Condition\ExitCodeCondition
     */
    public function createCondition(array $condition);
}
