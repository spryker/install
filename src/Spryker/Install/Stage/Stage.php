<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Stage;

use Spryker\Install\Stage\Section\Exception\SectionExistsException;
use Spryker\Install\Stage\Section\Exception\SectionNotFoundException;
use Spryker\Install\Stage\Section\SectionInterface;

class Stage implements StageInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Install\Stage\Section\SectionInterface[]
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
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @throws \Spryker\Install\Stage\Section\Exception\SectionExistsException
     *
     * @return \Spryker\Install\Stage\StageInterface
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
     * @return \Spryker\Install\Stage\Section\SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string $sectionName
     *
     * @throws \Spryker\Install\Stage\Section\Exception\SectionNotFoundException
     *
     * @return \Spryker\Install\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface
    {
        if (!isset($this->sections[$sectionName])) {
            throw new SectionNotFoundException(sprintf('Section "%s" not found in "%s" stage', $sectionName, $this->name));
        }

        return $this->sections[$sectionName];
    }
}
