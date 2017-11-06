<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder\Section\Command;

use Spryker\Setup\Stage\Section\Command\Command;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface;

class CommandBuilder implements CommandBuilderInterface
{
    const CONFIG_ENV = 'env';
    const CONFIG_COMMAND = 'command';
    const CONFIG_STORES = 'stores';
    const CONFIG_EXCLUDED = 'excluded';
    const CONFIG_GROUPS = 'groups';
    const CONFIG_PRE_COMMAND = 'pre';
    const CONFIG_POST_COMMAND = 'post';
    const CONFIG_CONDITIONS = 'conditions';
    const CONFIG_BREAK_ON_FAILURE = 'breakOnFailure';

    /**
     * @var \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    protected $conditionFactory;

    /**
     * @param \Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface $conditionFactory
     */
    public function __construct(ConditionFactoryInterface $conditionFactory)
    {
        $this->conditionFactory = $conditionFactory;
    }

    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Setup\Stage\Section\Command\Command
     */
    public function buildCommand($name, array $definition)
    {
        $command = new Command($name);
        $command->setExecutable($definition[static::CONFIG_COMMAND]);

        $this->setExcluded($command, $definition);
        $this->setGroups($command, $definition);
        $this->setEnv($command, $definition);
        $this->setIsStoreAware($command, $definition);
        $this->addCommandConditions($command, $definition);
        $this->setPreCommand($command, $definition);
        $this->setPostCommand($command, $definition);
        $this->setBreakOnFailure($command, $definition);

        return $command;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setExcluded(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_EXCLUDED]) && $definition[static::CONFIG_EXCLUDED]) {
            $command->markAsExcluded();
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setGroups(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_GROUPS])) {
            $command->setGroups($definition[static::CONFIG_GROUPS]);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setEnv(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_ENV])) {
            $command->setEnv($definition[static::CONFIG_ENV]);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setIsStoreAware(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_STORES])) {
            $command->setIsStoreAware($definition[static::CONFIG_STORES]);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function addCommandConditions(CommandInterface $command, array $definition)
    {
        if (!isset($definition[static::CONFIG_CONDITIONS])) {
            return;
        }

        foreach ($definition[static::CONFIG_CONDITIONS] as $condition) {
            $condition = $this->conditionFactory->createCondition($condition);
            $command->addCondition($condition);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setPreCommand(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_PRE_COMMAND])) {
            $command->setPreCommand($definition[static::CONFIG_PRE_COMMAND]);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setPostCommand(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_POST_COMMAND])) {
            $command->setPostCommand($definition[static::CONFIG_POST_COMMAND]);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setBreakOnFailure(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_BREAK_ON_FAILURE])) {
            $command->setBreakOnFailure($definition[static::CONFIG_BREAK_ON_FAILURE]);
        }
    }
}
