<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner\Environment;

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
