<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Configuration\Filter;

class UnsetFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $keyToUnset;

    /**
     * @param string $keyToUnset
     */
    public function __construct(string $keyToUnset)
    {
        $this->keyToUnset = $keyToUnset;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items): array
    {
        if (isset($items[$this->keyToUnset])) {
            unset($items[$this->keyToUnset]);
        }

        return $items;
    }
}
