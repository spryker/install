<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Stage\Section\Command;

use Spryker\Install\Stage\Section\Command\Condition\ConditionInterface;

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
    public function setExecutable(string $executable): self;

    /**
     * @return string
     */
    public function getExecutable(): string;

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env): self;

    /**
     * @return array
     */
    public function getEnv(): array;

    /**
     * @param array $groups
     *
     * @return $this
     */
    public function setGroups(array $groups): self;

    /**
     * @param bool $isStoreAware
     *
     * @return $this
     */
    public function setIsStoreAware(bool $isStoreAware): self;

    /**
     * @return bool
     */
    public function isStoreAware(): bool;

    /**
     * @param string[] $stores
     *
     * @return $this
     */
    public function setStores(array $stores): self;

    /**
     * @return string[]
     */
    public function getStores(): array;

    /**
     * @return bool
     */
    public function hasStores(): bool;

    /**
     * @param \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition): self;

    /**
     * @return \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions(): array;

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand(string $preCommand): self;

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
    public function setPostCommand(string $postCommand): self;

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
    public function markAsExcluded(): self;

    /**
     * @return bool
     */
    public function isExcluded(): bool;

    /**
     * @param bool $breakOnFailure
     *
     * @return $this
     */
    public function setBreakOnFailure(bool $breakOnFailure): self;

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
    public function setTimeout(int $timeout): self;

    /**
     * @return int
     */
    public function getTimeout(): int;
}
