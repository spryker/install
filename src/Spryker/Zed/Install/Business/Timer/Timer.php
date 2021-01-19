<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Timer;

class Timer implements TimerInterface
{
    /**
     * @var array
     */
    protected $timer = [];

    /**
     * @param object $object
     *
     * @return \Spryker\Zed\Install\Business\Timer\TimerInterface
     */
    public function start(object $object): TimerInterface
    {
        $this->timer[spl_object_hash($object)] = microtime(true);

        return $this;
    }

    /**
     * @param object $object
     *
     * @return float
     */
    public function end(object $object): float
    {
        $start = $this->timer[spl_object_hash($object)];
        $runtime = microtime(true) - $start;

        return round($runtime, 2);
    }
}
