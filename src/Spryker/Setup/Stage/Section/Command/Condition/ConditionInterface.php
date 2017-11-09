<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command\Condition;

interface ConditionInterface
{
    /**
     * @param array $exitCodes
     *
     * @return bool
     */
    public function match(array $exitCodes): bool;
}
