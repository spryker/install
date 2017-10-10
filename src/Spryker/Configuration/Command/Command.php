<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Configuration\Command;

class Command implements CommandConfigurationInterface, CommandInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Command\CommandInterface|callable|string
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
    protected $isExcluded = false;

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
     * @param \Spryker\Command\CommandInterface|string $executable
     *
     * @return $this
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;

        return $this;
    }

    /**
     * @return \Spryker\Command\CommandInterface|callable|string
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
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
     * @param bool $isExcluded
     *
     * @return $this
     */
    public function setIsExcluded($isExcluded)
    {
        $this->isExcluded = $isExcluded;

        return $this;
    }

    /**
     * @return void
     */
    public function isExcluded()
    {
        $this->isExcluded;
    }


}
