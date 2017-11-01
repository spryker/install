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
     * @param array $groups
     *
     * @return $this
     */
    public function setGroups(array $groups);

    /**
     * @param bool $isStoreAware
     *
     * @return $this
     */
    public function setIsStoreAware($isStoreAware);

    /**
     * @param \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition);

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand($preCommand);

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand($postCommand);

    /**
     * @return $this
     */
    public function markAsExcluded();
}
