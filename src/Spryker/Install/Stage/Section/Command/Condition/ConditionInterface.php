<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Stage\Section\Command\Condition;

interface ConditionInterface
{
    /**
     * @param array $exitCodes
     *
     * @return bool
     */
    public function match(array $exitCodes): bool;
}
