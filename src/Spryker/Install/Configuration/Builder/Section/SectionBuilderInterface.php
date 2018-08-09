<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Configuration\Builder\Section;

use Spryker\Install\Stage\Section\SectionInterface;

interface SectionBuilderInterface
{
    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Install\Stage\Section\SectionInterface
     */
    public function buildSection(string $name, array $definition): SectionInterface;
}
