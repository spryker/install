<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage;

use Spryker\Setup\Stage\Section\SectionInterface;

interface StageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @return $this
     */
    public function addSection(SectionInterface $section): self;

    /**
     * @return \Spryker\Setup\Stage\Section\SectionInterface[]
     */
    public function getSections(): array;

    /**
     * @param string $sectionName
     *
     * @return \Spryker\Setup\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface;
}
