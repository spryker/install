<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Stage;

use Spryker\Deploy\Stage\Section\SectionInterface;

interface StageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Spryker\Deploy\Stage\Section\SectionInterface $section
     *
     * @return $this
     */
    public function addSection(SectionInterface $section): self;

    /**
     * @return \Spryker\Deploy\Stage\Section\SectionInterface[]
     */
    public function getSections(): array;

    /**
     * @param string $sectionName
     *
     * @return \Spryker\Deploy\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface;
}
