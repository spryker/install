<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder\Section;

use Spryker\Setup\Stage\Section\SectionInterface;

interface SectionBuilderInterface
{
    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Setup\Stage\Section\SectionInterface
     */
    public function buildSection(string $name, array $definition): SectionInterface;
}
