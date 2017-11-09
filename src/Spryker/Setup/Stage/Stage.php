<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage;

use Spryker\Setup\Stage\Section\Exception\SectionExistsException;
use Spryker\Setup\Stage\Section\Exception\SectionNotFoundException;
use Spryker\Setup\Stage\Section\SectionInterface;

class Stage implements StageInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Setup\Stage\Section\SectionInterface[]
     */
    protected $sections = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @throws \Spryker\Setup\Stage\Section\Exception\SectionExistsException
     *
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function addSection(SectionInterface $section): StageInterface
    {
        if (isset($this->sections[$section->getName()])) {
            throw new SectionExistsException(sprintf('Section with name "%s" already exists.', $section->getName()));
        }

        $this->sections[$section->getName()] = $section;

        return $this;
    }

    /**
     * @return \Spryker\Setup\Stage\Section\SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string $sectionName
     *
     * @throws \Spryker\Setup\Stage\Section\Exception\SectionNotFoundException
     *
     * @return \Spryker\Setup\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface
    {
        if (!isset($this->sections[$sectionName])) {
            throw new SectionNotFoundException(sprintf('Section "%s" not found in "%s" stage', $sectionName, $this->name));
        }

        return $this->sections[$sectionName];
    }
}
