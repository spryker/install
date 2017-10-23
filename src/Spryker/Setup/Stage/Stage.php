<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage;

use Spryker\Setup\Configuration\Stage\StageConfigurationInterface;
use Spryker\Setup\Stage\Section\Exception\SectionExistsException;
use Spryker\Setup\Stage\Section\SectionInterface;

class Stage implements StageConfigurationInterface, StageInterface
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
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @throws \Spryker\Setup\Stage\Section\Exception\SectionExistsException
     *
     * @return $this
     */
    public function addSection(SectionInterface $section)
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
    public function getSections()
    {
        return $this->sections;
    }
}
