<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Stage\Section\Command;

use Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface;

interface CommandConfigurationInterface
{
    /**
     * @param string $executable
     *
     * @return $this
     */
    public function setExecutable($executable);

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @param \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition);
}
