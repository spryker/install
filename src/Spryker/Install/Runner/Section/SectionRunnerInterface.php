<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Runner\Section;

use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Stage\Section\SectionInterface;

interface SectionRunnerInterface
{
    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration);
}
