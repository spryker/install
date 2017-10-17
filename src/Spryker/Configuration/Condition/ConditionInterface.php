<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Condition;

interface ConditionInterface
{
    /**
     * @param array $exitCodes
     *
     * @return bool
     */
    public function match(array $exitCodes);
}
