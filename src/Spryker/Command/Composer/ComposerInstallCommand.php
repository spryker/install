<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Command\Composer;

use DateTime;
use Spryker\Command\CommandInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Process\Process;

class ComposerInstallCommand implements CommandInterface
{
    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
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
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return int|null
     */
    protected function updateComposer(StyleInterface $output)
    {
        $fileMTime = (new DateTime())->setTimestamp(filemtime($this->getPathToComposer()));
        $thirtyDaysAgo = new DateTime('- 30 days');

        if ($fileMTime < $thirtyDaysAgo) {
            $output->note('Composer is older then 30 days, update composer... ');

            $process = new Process('php composer.phar self-update', SPRYKER_ROOT);
            $process->run(
                function ($type, $buffer) use ($output) {
                    if (Process::ERR === $type) {
                        $output->error($buffer);
                    }
                }
            );

            return $process->getExitCode();
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @return int|null
     */
    protected function installComposer(StyleInterface $style)
    {
        $style->note('Composer not installed, start download...');

        $process = new Process('curl -sS https://getcomposer.org/installer | php', SPRYKER_ROOT);
        $process->run(
            function ($type, $buffer) use ($style) {
                if (Process::ERR === $type) {
                    $style->error($buffer);
                }
            }
        );

        return $process->getExitCode();
    }
}
