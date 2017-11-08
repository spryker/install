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
     * @return $this
     */
    public function start($object)
    {
        $this->timer[spl_object_hash($object)] = microtime(true);

        return $this;
    }

    /**
     * @param object $object
     *
     * @return string
     */
    public function end($object)
    {
        $start = $this->timer[spl_object_hash($object)];

        return sprintf('%.2f', microtime(true) - $start);
    }
}
