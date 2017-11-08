<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Logger;

class SetupOutputLogger implements SetupLoggerInterface
{
    /**
     * @var string
     */
    protected $pathToLogFile;

    public function __construct()
    {
        $this->pathToLogFile = SPRYKER_ROOT . '/.spryker/setup/logs/' . time() . '.log';
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function log($message)
    {
        $this->createDirectoryIfNotExists();

        file_put_contents($this->pathToLogFile, $message);
    }

    /**
     * @return void
     */
    protected function createDirectoryIfNotExists()
    {
        $directory = dirname($this->pathToLogFile);
        if (is_dir($directory) || !is_writable($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}
