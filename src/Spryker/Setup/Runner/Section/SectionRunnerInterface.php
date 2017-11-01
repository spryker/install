<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner\Section;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Stage\Section\SectionInterface;

interface SectionRunnerInterface
{
    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration);
}
