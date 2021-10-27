<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Filter;

class CommandExcludeFilter implements FilterInterface
{
    /**
     * @var string
     */
    public const GROUPS = 'groups';

    /**
     * @var string[]
     */
    protected $includeExcluded;

    /**
     * @var string[]
     */
    protected $groupsToBeExecuted;

    /**
     * @var string[]
     */
    protected $excludedCommandsAndGroups;

    /**
     * @param string[] $includeExcluded
     * @param string[] $groupsToBeExecuted
     * @param string[] $excludedCommandsAndGroups
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
    public function filter(array $items): array
    {
        $filtered = [];
        foreach ($items as $commandName => $commandDefinition) {
            if ($commandName === static::EXCLUDED) {
                continue;
            }

            $isExcluded = true;

            if ($this->shouldCommandBeAdded($commandName, $commandDefinition)) {
                $isExcluded = false;
            }

            $commandDefinition[static::EXCLUDED] = $isExcluded;
            $filtered[$commandName] = $commandDefinition;
        }

        return $filtered;
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function shouldCommandBeAdded(string $commandName, array $commandDefinition): bool
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
    protected function getCommandGroups(array $commandDefinition): array
    {
        return (isset($commandDefinition[static::GROUPS])) ? $commandDefinition[static::GROUPS] : [];
    }

    /**
     * @return bool
     */
    protected function runOnlySpecified(): bool
    {
        return (count($this->groupsToBeExecuted) > 0);
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     * @param string[] $commandGroups
     *
     * @return bool
     */
    protected function isCommandExcluded(string $commandName, array $commandDefinition, array $commandGroups): bool
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
    protected function isExcludedByName(string $commandName): bool
    {
        return in_array($commandName, $this->excludedCommandsAndGroups);
    }

    /**
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function isExcludedByDefinition(array $commandDefinition): bool
    {
        return (isset($commandDefinition[static::EXCLUDED])) ? $commandDefinition[static::EXCLUDED] : false;
    }

    /**
     * @param string[] $commandGroups
     *
     * @return bool
     */
    protected function isGroupExcluded(array $commandGroups): bool
    {
        return (count($this->excludedCommandsAndGroups) > 0 && (count(array_intersect($this->excludedCommandsAndGroups, $commandGroups)) > 0));
    }

    /**
     * @param string[] $commandGroups
     *
     * @return bool
     */
    protected function isGroupRequested(array $commandGroups): bool
    {
        return (count($this->groupsToBeExecuted) > 0 && (count(array_intersect($this->groupsToBeExecuted, $commandGroups)) > 0));
    }

    /**
     * @param string $commandName
     * @param string[] $commandGroups
     *
     * @return bool
     */
    protected function shouldBeIncluded(string $commandName, array $commandGroups): bool
    {
        if (count($this->includeExcluded) > 0) {
            return (in_array($commandName, $this->includeExcluded) || count(array_intersect($this->includeExcluded, $commandGroups)) > 0);
        }

        return false;
    }
}
