<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Runner\Environment;

interface EnvironmentHelperInterface
{
    /**
     * @param array $env
     *
     * @return void
     */
    public function putEnvs(array $env);

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function putEnv(string $key, $value);

    /**
     * @param array $env
     *
     * @return void
     */
    public function unsetEnvs(array $env);

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetEnv(string $key);
}
