<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner\Section;

use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Stage\Section\SectionInterface;

interface SectionRunnerInterface
{
    /**
     * @param \Spryker\Deploy\Stage\Section\SectionInterface $section
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration);
}
