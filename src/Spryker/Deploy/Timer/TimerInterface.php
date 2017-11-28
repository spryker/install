<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Timer;

interface TimerInterface
{
    /**
     * @param object $object
     *
     * @return $this
     */
    public function start($object): self;

    /**
     * @param object $object
     *
     * @return float
     */
    public function end($object): float;
}
