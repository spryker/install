<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\CommandLine;

class CommandLineOptionContainer
{
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
        array $requestedSections,
        array $requestedGroups,
        array $exclude,
        array $includeExcluded,
        bool $isInteractive,
        bool $isDryRun,
        bool $isDebugMode,
        bool $askBeforeContinueOnException
    ) {
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
     * @return array
     */
    public function getRequestedSections(): array
    {
        return $this->requestedSections;
    }

    /**
     * @return array
     */
    public function getRequestedGroups(): array
    {
        return $this->requestedGroups;
    }

    /**
     * @return array
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }

    /**
     * @return array
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
