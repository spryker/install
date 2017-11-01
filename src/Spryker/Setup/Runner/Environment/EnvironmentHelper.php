<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner\Environment;

class EnvironmentHelper implements EnvironmentHelperInterface
{
    /**
     * @param array $env
     *
     * @return void
     */
    public function putEnvs(array $env)
    {
        foreach ($env as $key => $value) {
            $this->putEnv($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function putEnv($key, $value)
    {
        putenv(sprintf('%s=%s', $key, $value));
    }

    /**
     * @param array $env
     *
     * @return void
     */
    public function unsetEnvs(array $env)
    {
        foreach (array_keys($env) as $key) {
            $this->unsetEnv($key);
        }
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetEnv($key)
    {
        putenv($key);
    }
}
