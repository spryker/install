<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
