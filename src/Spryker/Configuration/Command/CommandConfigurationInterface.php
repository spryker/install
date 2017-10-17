<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Command;

interface CommandConfigurationInterface
{
    /**
     * @param string $executable
     *
     * @return $this
     */
    public function setExecutable($executable);

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);
}
