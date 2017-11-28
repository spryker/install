<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Stage;

use Spryker\Deploy\Stage\Section\Exception\SectionExistsException;
use Spryker\Deploy\Stage\Section\Exception\SectionNotFoundException;
use Spryker\Deploy\Stage\Section\SectionInterface;

class Stage implements StageInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Deploy\Stage\Section\SectionInterface[]
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
     * @param \Spryker\Deploy\Stage\Section\SectionInterface $section
     *
     * @throws \Spryker\Deploy\Stage\Section\Exception\SectionExistsException
     *
     * @return \Spryker\Deploy\Stage\StageInterface
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
     * @return \Spryker\Deploy\Stage\Section\SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string $sectionName
     *
     * @throws \Spryker\Deploy\Stage\Section\Exception\SectionNotFoundException
     *
     * @return \Spryker\Deploy\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface
    {
        if (!isset($this->sections[$sectionName])) {
            throw new SectionNotFoundException(sprintf('Section "%s" not found in "%s" stage', $sectionName, $this->name));
        }

        return $this->sections[$sectionName];
    }
}
