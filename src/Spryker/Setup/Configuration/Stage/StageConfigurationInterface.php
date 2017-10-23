<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Stage;

use Spryker\Setup\Stage\Section\SectionInterface;

interface StageConfigurationInterface
{
    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @return $this
     */
    public function addSection(SectionInterface $section);
}
