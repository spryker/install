<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner;

class Timer implements TimerInterface
{
    /**
     * @var float
     */
    protected $start;

    /**
     * @var float
     */
    protected $end;

    /**
     * @return $this
     */
    public function start()
    {
        $this->start = $this->getMicrotime();

        return $this;
    }

    /**
     * @return $this
     */
    public function end()
    {
        $this->end = $this->getMicrotime();

        return $this;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getRuntime($format = '%.2f')
    {
        return sprintf($format, $this->end - $this->start);
    }

    /**
     * @return float
     */
    private function getMicrotime()
    {
        return microtime(true);
    }
}
