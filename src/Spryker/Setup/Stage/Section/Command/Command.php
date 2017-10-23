<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command;

use Spryker\Setup\Configuration\Stage\Section\Command\CommandConfigurationInterface;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface;

class Command implements CommandConfigurationInterface, CommandInterface
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
     * @var \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface[]
     */
    protected $conditions = [];

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
    public function setGroups($groups)
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
}
