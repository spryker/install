<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Stage\Section\Command\Condition;

class ExitCodeCondition extends AbstractCondition
{
    /**
     * @param int[] $exitCodes
     *
     * @return bool
     */
    public function match(array $exitCodes): bool
    {
        if (!isset($exitCodes[$this->command]) || $exitCodes[$this->command] !== $this->exitCode) {
            return false;
        }

        return true;
    }
}
