<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command;

use Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface;

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
     * @var \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface[]
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
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $executable
     *
     * @return $this
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;

        return $this;
    }

    /**
     * @return string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @param array $groups
     *
     * @return $this
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param bool $isStoreAware
     *
     * @return $this
     */
    public function setIsStoreAware($isStoreAware)
    {
        $this->isStoreAware = $isStoreAware;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStoreAware()
    {
        return $this->isStoreAware;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface $condition
     *
     * @return $this
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand($preCommand)
    {
        $this->preCommand = $preCommand;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPreCommand()
    {
        return ($this->preCommand !== null);
    }

    /**
     * @return string
     */
    public function getPreCommand()
    {
        return $this->preCommand;
    }

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand($postCommand)
    {
        $this->postCommand = $postCommand;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPostCommand()
    {
        return ($this->postCommand !== null);
    }

    /**
     * @return string
     */
    public function getPostCommand()
    {
        return $this->postCommand;
    }

    /**
     * @return $this
     */
    public function markAsExcluded()
    {
        $this->isExcluded = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return $this->isExcluded;
    }

    /**
     * @param bool $breakOnFailure
     *
     * @return void
     */
    public function setBreakOnFailure($breakOnFailure)
    {
        $this->breakOnFailure = $breakOnFailure;
    }

    /**
     * @return bool
     */
    public function breakOnFailure()
    {
        return $this->breakOnFailure;
    }
}
