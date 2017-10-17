<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Filter;

interface FilterInterface
{
    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items);
}
