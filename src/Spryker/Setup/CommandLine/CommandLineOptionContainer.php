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
     * @param array $requestedSections
     * @param array $requestedGroups
     * @param array $exclude
     * @param array $includeExcluded
     * @param bool $isInteractive
     */
    public function __construct(array $requestedSections, array $requestedGroups, array $exclude, array $includeExcluded, $isInteractive)
    {
        $this->requestedSections = $requestedSections;
        $this->requestedGroups = $requestedGroups;
        $this->exclude = $exclude;
        $this->includeExcluded = $includeExcluded;
        $this->isInteractive = $isInteractive;
    }

    /**
     * @return array
     */
    public function getRequestedSections()
    {
        return $this->requestedSections;
    }

    /**
     * @return array
     */
    public function getRequestedGroups()
    {
        return $this->requestedGroups;
    }

    /**
     * @return array
     */
    public function getExclude()
    {
        return $this->exclude;
    }

    /**
     * @return array
     */
    public function getIncludeExcluded()
    {
        return $this->includeExcluded;
    }

    /**
     * @return bool
     */
    public function isInteractive()
    {
        return $this->isInteractive;
    }
}
