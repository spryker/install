<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage;

use Spryker\Zed\Install\Business\Stage\Section\Exception\SectionExistsException;
use Spryker\Zed\Install\Business\Stage\Section\Exception\SectionNotFoundException;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;

class Stage implements StageInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Zed\Install\Business\Stage\Section\SectionInterface[]
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @throws \Spryker\Zed\Install\Business\Stage\Section\Exception\SectionExistsException
     *
     * @return \Spryker\Zed\Install\Business\Stage\StageInterface
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
     * @return \Spryker\Zed\Install\Business\Stage\Section\SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string $sectionName
     *
     * @throws \Spryker\Zed\Install\Business\Stage\Section\Exception\SectionNotFoundException
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface
    {
        if (!isset($this->sections[$sectionName])) {
            throw new SectionNotFoundException(sprintf('Section "%s" not found in "%s" stage', $sectionName, $this->name));
        }

        return $this->sections[$sectionName];
    }
}
