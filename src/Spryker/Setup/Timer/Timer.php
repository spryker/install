<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Timer;

class Timer implements TimerInterface
{
    /**
     * @var array
     */
    protected $timer = [];

    /**
     * @param object $object
     *
     * @return \Spryker\Setup\Timer\TimerInterface
     */
    public function start($object): TimerInterface
    {
        $this->timer[spl_object_hash($object)] = microtime(true);

        return $this;
    }

    /**
     * @param object $object
     *
     * @return float
     */
    public function end($object): float
    {
        $start = $this->timer[spl_object_hash($object)];

        return sprintf('%.2f', microtime(true) - $start);
    }
}
