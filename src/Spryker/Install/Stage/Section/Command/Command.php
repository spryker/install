<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Stage\Section\Command;

use Spryker\Install\Stage\Section\Command\Condition\ConditionInterface;

class Command implements CommandInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $executable;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @var bool
     */
    protected $isStoreAware = false;

    /**
     * @var bool
     */
    protected $isExcluded = false;

    /**
     * @var \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @var string
     */
    protected $preCommand;

    /**
     * @var string
     */
    protected $postCommand;

    /**
     * @var bool
     */
    protected $breakOnFailure = true;

    /**
     * @var string[]
     */
    protected $stores = [];

    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $executable
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setExecutable(string $executable): CommandInterface
    {
        $this->executable = $executable;

        return $this;
    }

    /**
     * @return string
     */
    public function getExecutable(): string
    {
        return $this->executable;
    }

    /**
     * @param array $groups
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setGroups(array $groups): CommandInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param array $env
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setEnv(array $env): CommandInterface
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * @param bool $isStoreAware
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setIsStoreAware(bool $isStoreAware): CommandInterface
    {
        $this->isStoreAware = $isStoreAware;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStoreAware(): bool
    {
        return $this->isStoreAware;
    }

    /**
     * @param string[] $stores
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setStores(array $stores): CommandInterface
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getStores(): array
    {
        return $this->stores;
    }

    /**
     * @return bool
     */
    public function hasStores(): bool
    {
        return (count($this->stores) > 0);
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function addCondition(ConditionInterface $condition): CommandInterface
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @return \Spryker\Install\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param string $preCommand
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setPreCommand(string $preCommand): CommandInterface
    {
        $this->preCommand = $preCommand;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPreCommand(): bool
    {
        return ($this->preCommand !== null);
    }

    /**
     * @return string
     */
    public function getPreCommand(): string
    {
        return $this->preCommand;
    }

    /**
     * @param string $postCommand
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setPostCommand(string $postCommand): CommandInterface
    {
        $this->postCommand = $postCommand;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPostCommand(): bool
    {
        return ($this->postCommand !== null);
    }

    /**
     * @return string
     */
    public function getPostCommand(): string
    {
        return $this->postCommand;
    }

    /**
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function markAsExcluded(): CommandInterface
    {
        $this->isExcluded = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExcluded(): bool
    {
        return $this->isExcluded;
    }

    /**
     * @param bool $breakOnFailure
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setBreakOnFailure(bool $breakOnFailure): CommandInterface
    {
        $this->breakOnFailure = $breakOnFailure;

        return $this;
    }

    /**
     * @return bool
     */
    public function breakOnFailure(): bool
    {
        return $this->breakOnFailure;
    }

    /**
     * @return bool
     */
    public function hasTimeout(): bool
    {
        return ($this->timeout !== 0);
    }

    /**
     * @param int $timeout
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function setTimeout(int $timeout): CommandInterface
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
