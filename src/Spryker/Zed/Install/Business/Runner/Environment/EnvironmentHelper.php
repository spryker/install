<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Environment;

class EnvironmentHelper implements EnvironmentHelperInterface
{
    /**
     * @param array $env
     *
     * @return void
     */
    public function putEnvs(array $env): void
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
    public function putEnv(string $key, $value): void
    {
        putenv(sprintf('%s=%s', $key, $value));
    }

    /**
     * @param array $env
     *
     * @return void
     */
    public function unsetEnvs(array $env): void
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
    public function unsetEnv(string $key): void
    {
        putenv($key);
    }
}
