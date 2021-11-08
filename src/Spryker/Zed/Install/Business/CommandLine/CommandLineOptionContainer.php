<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\CommandLine;

class CommandLineOptionContainer
{
    /**
     * @var string
     */
    protected $recipe;

    /**
     * @var array
     */
    protected $requestedSections;

    /**
     * @var array
     */
    protected $requestedGroups;

    /**
     * @var array
     */
    protected $exclude;

    /**
     * @var array
     */
    protected $includeExcluded;

    /**
     * @var bool
     */
    protected $isInteractive;

    /**
     * @var bool
     */
    protected $isDryRun;

    /**
     * @var bool
     */
    protected $isDebugMode;

    /**
     * @var bool
     */
    protected $askBeforeContinueOnException;

    /**
     * @param string $recipe
     * @param array $requestedSections
     * @param array $requestedGroups
     * @param array $exclude
     * @param array $includeExcluded
     * @param bool $isInteractive
     * @param bool $isDryRun
     * @param bool $isDebugMode
     * @param bool $askBeforeContinueOnException
     */
    public function __construct(
        string $recipe,
        array $requestedSections,
        array $requestedGroups,
        array $exclude,
        array $includeExcluded,
        bool $isInteractive,
        bool $isDryRun,
        bool $isDebugMode,
        bool $askBeforeContinueOnException
    ) {
        $this->recipe = $recipe;
        $this->requestedSections = $requestedSections;
        $this->requestedGroups = $requestedGroups;
        $this->exclude = $exclude;
        $this->includeExcluded = $includeExcluded;
        $this->isInteractive = $isInteractive;
        $this->isDryRun = $isDryRun;
        $this->isDebugMode = $isDebugMode;
        $this->askBeforeContinueOnException = $askBeforeContinueOnException;
    }

    /**
     * @return string
     */
    public function getRecipe(): string
    {
        return $this->recipe;
    }

    /**
     * @return array<string>
     */
    public function getRequestedSections(): array
    {
        return $this->requestedSections;
    }

    /**
     * @return array<string>
     */
    public function getRequestedGroups(): array
    {
        return $this->requestedGroups;
    }

    /**
     * @return array<string>
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }

    /**
     * @return array<string>
     */
    public function getIncludeExcluded(): array
    {
        return $this->includeExcluded;
    }

    /**
     * @return bool
     */
    public function isInteractive(): bool
    {
        return $this->isInteractive;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->isDryRun;
    }

    /**
     * @return bool
     */
    public function isDebugMode(): bool
    {
        return $this->isDebugMode;
    }

    /**
     * @return bool
     */
    public function askBeforeContinueOnException(): bool
    {
        return $this->askBeforeContinueOnException;
    }
}
