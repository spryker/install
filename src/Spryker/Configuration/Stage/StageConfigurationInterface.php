<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Stage;

use Spryker\Configuration\Section\SectionInterface;

interface StageConfigurationInterface
{
    /**
     * @param \Spryker\Configuration\Section\SectionInterface $section
     *
     * @return $this
     */
    public function addSection(SectionInterface $section);
}
