<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner;

interface TimerInterface
{
    /**
     * @return $this
     */
    public function start();

    /**
     * @return $this
     */
    public function end();

    /**
     * @param string $format
     *
     * @return string
     */
    public function getRuntime($format = '%.2f');
}
