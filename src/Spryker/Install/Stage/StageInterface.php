<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Stage;

use Spryker\Install\Stage\Section\SectionInterface;

interface StageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @return $this
     */
    public function addSection(SectionInterface $section);

    /**
     * @return \Spryker\Install\Stage\Section\SectionInterface[]
     */
    public function getSections(): array;

    /**
     * @param string $sectionName
     *
     * @return \Spryker\Install\Stage\Section\SectionInterface
     */
    public function getSection(string $sectionName): SectionInterface;
}
