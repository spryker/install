<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
