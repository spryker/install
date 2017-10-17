<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Command;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getExecutable();

    /**
     * @return array
     */
    public function getEnv();

    /**
     * @return \Spryker\Configuration\Condition\ConditionInterface[]
     */
    public function getConditions();
}
