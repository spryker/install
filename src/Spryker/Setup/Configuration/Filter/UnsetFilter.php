<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Filter;

class UnsetFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $keyToUnset;

    /**
     * @param string $keyToUnset
     */
    public function __construct($keyToUnset)
    {
        $this->keyToUnset = $keyToUnset;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items)
    {
        if (isset($items[$this->keyToUnset])) {
            unset($items[$this->keyToUnset]);
        }

        return $items;
    }
}
