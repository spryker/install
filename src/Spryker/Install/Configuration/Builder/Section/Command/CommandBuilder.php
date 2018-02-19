<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Builder\Section\Command;

use Spryker\Install\Stage\Section\Command\Command;
use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Install\Stage\Section\Command\Condition\ConditionFactoryInterface;

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
    const CONFIG_TIMEOUT = 'timeout';

    /**
     * @var \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Install\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    protected $conditionFactory;

    /**
     * @param \Spryker\Install\Stage\Section\Command\Condition\ConditionFactoryInterface $conditionFactory
     */
    public function __construct(ConditionFactoryInterface $conditionFactory)
    {
        $this->conditionFactory = $conditionFactory;
    }

    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function buildCommand(string $name, array $definition): CommandInterface
    {
        $command = new Command($name);
        $command->setExecutable($definition[static::CONFIG_COMMAND]);

        $this->setExcluded($command, $definition);
        $this->setGroups($command, $definition);
        $this->setEnv($command, $definition);
        $this->setIsStoreAware($command, $definition);
        $this->setStores($command, $definition);
        $this->addCommandConditions($command, $definition);
        $this->setPreCommand($command, $definition);
        $this->setPostCommand($command, $definition);
        $this->setBreakOnFailure($command, $definition);
        $this->setTimeout($command, $definition);

        return $command;
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setIsStoreAware(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_STORES])) {
            $command->setIsStoreAware($this->getIsStoreAware($definition));
        }
    }

    /**
     * @param array $definition
     *
     * @return bool
     */
    protected function getIsStoreAware(array $definition)
    {
        return (is_array($definition[static::CONFIG_STORES])) ? true : $definition[static::CONFIG_STORES];
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setStores(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_STORES]) && is_array($definition[static::CONFIG_STORES])) {
            $command->setStores($definition[static::CONFIG_STORES]);
        }
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param array $definition
     *
     * @return void
     */
    protected function setTimeout(CommandInterface $command, array $definition)
    {
        if (isset($definition[static::CONFIG_TIMEOUT])) {
            $command->setTimeout($definition[static::CONFIG_TIMEOUT]);
        }
    }
}
