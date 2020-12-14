<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Timer;

interface TimerInterface
{
    /**
     * @param object $object
     *
     * @return $this
     */
    public function start($object);

    /**
     * @param object $object
     *
     * @return float
     */
    public function end($object): float;
}
