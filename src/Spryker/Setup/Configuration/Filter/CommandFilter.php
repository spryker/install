<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Filter;

class CommandFilter implements FilterInterface
{
    const EXCLUDED = 'excluded';
    const GROUPS = 'groups';

    /**
     * @var array
     */
    protected $includeExcluded;

    /**
     * @var array
     */
    protected $groupsToBeExecuted;

    /**
     * @var array
     */
    protected $excludedCommandsAndGroups;

    /**
     * @var bool
     */
    protected $isSectionExcluded = false;

    /**
     * @param array $includeExcluded
     * @param array $groupsToBeExecuted
     * @param array $excludedCommandsAndGroups
     */
    public function __construct(array $includeExcluded, array $groupsToBeExecuted, array $excludedCommandsAndGroups)
    {
        $this->includeExcluded = $includeExcluded;
        $this->groupsToBeExecuted = $groupsToBeExecuted;
        $this->excludedCommandsAndGroups = $excludedCommandsAndGroups;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items)
    {
        $filtered = [];
        foreach ($items as $commandName => $commandDefinition) {
            if ($commandName === static::EXCLUDED) {
                $this->setIsSectionExcluded($commandDefinition);
                continue;
            }
            if ($this->shouldCommandBeAdded($commandName, $commandDefinition)) {
                $filtered[$commandName] = $commandDefinition;
            }
        }

        return $filtered;
    }

    /**
     * @param bool $isExcluded
     *
     * @return void
     */
    protected function setIsSectionExcluded($isExcluded)
    {
        $this->isSectionExcluded = $isExcluded;
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function shouldCommandBeAdded($commandName, array $commandDefinition)
    {
        $commandGroups = $this->getCommandGroups($commandDefinition);

        if ($this->isCommandExcluded($commandName, $commandDefinition, $commandGroups)) {
            return false;
        }

        if ($this->isGroupExcluded($commandGroups)) {
            return false;
        }

        if ($this->runOnlySpecified()) {
            return $this->isGroupRequested($commandGroups);
        }

        return true;
    }

    /**
     * @param array $commandDefinition
     *
     * @return array
     */
    protected function getCommandGroups(array $commandDefinition)
    {
        return (isset($commandDefinition[static::GROUPS])) ? $commandDefinition[static::GROUPS] : [];
    }

    /**
     * @return bool
     */
    protected function runOnlySpecified()
    {
        return (count($this->groupsToBeExecuted) > 0);
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     * @param array $commandGroups
     *
     * @return bool
     */
    protected function isCommandExcluded($commandName, array $commandDefinition, array $commandGroups)
    {
        if ($this->isExcludedByName($commandName)) {
            return true;
        }

        if ($this->isExcludedByDefinition($commandDefinition) && !$this->shouldBeIncluded($commandName, $commandGroups)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $commandName
     *
     * @return bool
     */
    protected function isExcludedByName($commandName)
    {
        return in_array($commandName, $this->excludedCommandsAndGroups);
    }

    /**
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function isExcludedByDefinition(array $commandDefinition)
    {
        return (isset($commandDefinition[static::EXCLUDED])) ? $commandDefinition[static::EXCLUDED] : false;
    }

    /**
     * @param array $commandGroups
     *
     * @return bool
     */
    protected function isGroupExcluded(array $commandGroups)
    {
        return (count($this->excludedCommandsAndGroups) > 0 && (count(array_intersect($this->excludedCommandsAndGroups, $commandGroups)) > 0));
    }

    /**
     * @param array $commandGroups
     *
     * @return bool
     */
    protected function isGroupRequested(array $commandGroups)
    {
        return (count($this->groupsToBeExecuted) > 0 && (count(array_intersect($this->groupsToBeExecuted, $commandGroups)) > 0));
    }

    /**
     * @param string $commandName
     * @param array $commandGroups
     *
     * @return bool
     */
    protected function shouldBeIncluded($commandName, array $commandGroups)
    {
        if (count($this->includeExcluded) > 0) {
            return (in_array($commandName, $this->includeExcluded) || count(array_intersect($this->includeExcluded, $commandGroups)) > 0);
        }

        return false;
    }
}
