<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Filter;

interface FilterInterface
{
    public const EXCLUDED = 'excluded';

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items): array;
}
