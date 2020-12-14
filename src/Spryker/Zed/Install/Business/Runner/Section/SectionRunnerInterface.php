<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Section;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;

interface SectionRunnerInterface
{
    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration);
}
