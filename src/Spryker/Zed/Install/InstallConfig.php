<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class InstallConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getEnvironment()
    {
        $environment = getenv('APPLICATION_ENV', true) ?: getenv('APPLICATION_ENV');

        if (!$environment && file_exists(APPLICATION_ROOT_DIR . '/config/Shared/console_env_local.php')) {
            $environment = require APPLICATION_ROOT_DIR . '/config/Shared/console_env_local.php';
        }

        if (!$environment) {
            $environment = 'production';
        }

        return (string)$environment;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return SPRYKER_ROOT . '/data/install/logs/' . date('Y-m-d_H-i-s') . '.log';
    }
}
