<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Environment;

interface EnvironmentHelperInterface
{
    /**
     * @param array $env
     *
     * @return void
     */
    public function putEnvs(array $env): void;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function putEnv(string $key, $value): void;

    /**
     * @param array $env
     *
     * @return void
     */
    public function unsetEnvs(array $env): void;

    /**
     * @param string $key
     *
     * @return void
     */
    public function unsetEnv(string $key): void;
}
