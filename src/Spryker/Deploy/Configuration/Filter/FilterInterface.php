<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Filter;

interface FilterInterface
{
    const EXCLUDED = 'excluded';

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items): array;
}
