<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Filter;

use Symfony\Component\Console\Style\SymfonyStyle;

class InteractiveSectionFilter implements FilterInterface
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * @param \Symfony\Component\Console\Style\SymfonyStyle $output
     */
    public function __construct(SymfonyStyle $output)
    {
        $this->output = $output;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items)
    {
        $filtered = [];

        foreach ($items as $sectionName => $sectionDefinition) {
            if ($this->output->confirm(sprintf('Should section "%s" be executed?', $sectionName)) === true) {
                $filtered[$sectionName] = $sectionDefinition;
            }
        }

        return $filtered;
    }
}
