<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner;

use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;

interface InstallRunnerInterface
{
    /**
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return void
     */
    public function run(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): void;
}
