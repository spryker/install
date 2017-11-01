<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Filter;

use Symfony\Component\Console\Style\StyleInterface;

class InteractiveSectionExcludeFilter implements FilterInterface
{
    /**
     * @var \Symfony\Component\Console\Style\StyleInterface
     */
    protected $output;

    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     */
    public function __construct(StyleInterface $output)
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
            $isExcluded = true;
            if ($this->output->confirm(sprintf('Should section <fg=yellow>%s</> be executed?', $sectionName)) === true) {
                $isExcluded = false;
            }
            $sectionDefinition[static::EXCLUDED] = $isExcluded;
            $filtered[$sectionName] = $sectionDefinition;
        }

        return $filtered;
    }
}
