<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section;

use Spryker\Setup\Configuration\Stage\Section\SectionConfigurationInterface;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\Command\Exception\CommandExistsException;

class Section implements SectionConfigurationInterface, SectionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Setup\Stage\Section\Command\CommandInterface[]
     */
    protected $commands = [];

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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @throws \Spryker\Setup\Stage\Section\Command\Exception\CommandExistsException
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command)
    {
        if (isset($this->commands[$command->getName()])) {
            throw new CommandExistsException(sprintf('Command with name "%s" already exists.', $command->getName()));
        }
        $this->commands[$command->getName()] = $command;

        return $this;
    }

    /**
     * @return void
     */
    public function markAsExcluded()
    {
        $this->isExcluded = true;
    }

    /**
     * @return bool
     */
    public function isExcluded()
    {
        return $this->isExcluded;
    }

    /**
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param string $commandName
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function getCommand($commandName)
    {
        return $this->commands[$commandName];
    }
}
