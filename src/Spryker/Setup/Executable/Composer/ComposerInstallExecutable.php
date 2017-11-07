<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable\Composer;

use DateTime;
use Spryker\Setup\Executable\ExecutableInterface;
use Spryker\Style\StyleInterface;
use Symfony\Component\Process\Process;

class ComposerInstallExecutable implements ExecutableInterface
{
    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int|null
     */
    public function execute(StyleInterface $output)
    {
        if ($this->isComposerInstalled()) {
            return $this->updateComposer($output);
        }

        return $this->installComposer($output);
    }

    /**
     * @return bool
     */
    protected function isComposerInstalled()
    {
        return is_file($this->getPathToComposer());
    }

    /**
     * @return string
     */
    protected function getPathToComposer()
    {
        return SPRYKER_ROOT . '/composer.phar';
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int|null
     */
    protected function updateComposer(StyleInterface $output)
    {
        if ($this->isComposerOutdated()) {
            return $this->runComposerUpdate($output);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return bool
     */
    protected function isComposerOutdated()
    {
        $fileMTime = (new DateTime())->setTimestamp(filemtime($this->getPathToComposer()));
        $thirtyDaysAgo = new DateTime('- 30 days');

        if ($fileMTime < $thirtyDaysAgo) {
            return true;
        }

        return false;
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int|null
     */
    protected function runComposerUpdate(StyleInterface $output)
    {
        $output->write('Composer is older then 30 days, update composer... ');

        $process = new Process('php composer.phar self-update', SPRYKER_ROOT);
        $process->run(
            function ($type, $buffer) use ($output) {
                if (Process::ERR === $type) {
                    $output->write($buffer);
                }
            }
        );

        return ($process->getExitCode() === null) ? static::CODE_SUCCESS : $process->getExitCode();
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int|null
     */
    protected function installComposer(StyleInterface $output)
    {
        $output->write('Composer not installed, start download...');

        $process = new Process('curl -sS https://getcomposer.org/installer | php', SPRYKER_ROOT);
        $process->run(
            function ($type, $buffer) use ($output) {
                if (Process::ERR === $type) {
                    $output->write($buffer);
                }
            }
        );

        return $process->getExitCode();
    }
}
