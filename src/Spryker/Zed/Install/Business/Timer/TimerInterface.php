<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Timer;

interface TimerInterface
{
    /**
     * @param object $object
     *
     * @return $this
     */
    public function start(object $object);

    /**
     * @param object $object
     *
     * @return float
     */
    public function end(object $object): float;
}
