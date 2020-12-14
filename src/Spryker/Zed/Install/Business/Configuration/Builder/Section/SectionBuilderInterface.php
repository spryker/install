<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Builder\Section;

use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;

interface SectionBuilderInterface
{
    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\SectionInterface
     */
    public function buildSection(string $name, array $definition): SectionInterface;
}
