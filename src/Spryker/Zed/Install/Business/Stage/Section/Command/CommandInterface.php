<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage\Section\Command;

use Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionInterface;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $executable
     *
     * @return $this
     */
    public function setExecutable(string $executable);

    /**
     * @return string
     */
    public function getExecutable(): string;

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @return array
     */
    public function getEnv(): array;

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
    public function setIsStoreAware(bool $isStoreAware);

    /**
     * @return bool
     */
    public function isStoreAware(): bool;

    /**
     * @param string[] $stores
     *
     * @return $this
     */
    public function setStores(array $stores);

    /**
     * @return string[]
     */
    public function getStores(): array;

    /**
     * @return bool
     */
    public function hasStores(): bool;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition);

    /**
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions(): array;

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand(string $preCommand);

    /**
     * @return bool
     */
    public function hasPreCommand(): bool;

    /**
     * @return string
     */
    public function getPreCommand(): string;

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand(string $postCommand);

    /**
     * @return bool
     */
    public function hasPostCommand(): bool;

    /**
     * @return string
     */
    public function getPostCommand(): string;

    /**
     * @return $this
     */
    public function markAsExcluded();

    /**
     * @return bool
     */
    public function isExcluded(): bool;

    /**
     * @param bool $breakOnFailure
     *
     * @return $this
     */
    public function setBreakOnFailure(bool $breakOnFailure);

    /**
     * @return bool
     */
    public function breakOnFailure(): bool;

    /**
     * @return bool
     */
    public function hasTimeout(): bool;

    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout);

    /**
     * @return int
     */
    public function getTimeout(): int;
}
