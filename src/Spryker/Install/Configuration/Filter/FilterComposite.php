<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Filter;

class FilterComposite implements FilterInterface
{
    /**
     * @var \Spryker\Install\Configuration\Filter\FilterInterface[]
     */
    protected $filter;

    /**
     * @param array $filter
     */
    public function __construct(array $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items): array
    {
        foreach ($this->filter as $filter) {
            $items = $filter->filter($items);
        }

        return $items;
    }
}
