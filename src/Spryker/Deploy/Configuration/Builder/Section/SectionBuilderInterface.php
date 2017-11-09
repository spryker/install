<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Builder\Section;

use Spryker\Deploy\Stage\Section\SectionInterface;

interface SectionBuilderInterface
{
    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Deploy\Stage\Section\SectionInterface
     */
    public function buildSection(string $name, array $definition): SectionInterface;
}
