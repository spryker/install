<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command;

use Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $executable
     *
     * @return $this
     */
    public function setExecutable($executable);

    /**
     * @return string
     */
    public function getExecutable();

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @return array
     */
    public function getEnv();

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
     * @return bool
     */
    public function isStoreAware();

    /**
     * @param \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition);

    /**
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions();

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand($preCommand);

    /**
     * @return bool
     */
    public function hasPreCommand();

    /**
     * @return string
     */
    public function getPreCommand();

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand($postCommand);

    /**
     * @return bool
     */
    public function hasPostCommand();

    /**
     * @return string
     */
    public function getPostCommand();

    /**
     * @return $this
     */
    public function markAsExcluded();

    /**
     * @return bool
     */
    public function isExcluded();
}
