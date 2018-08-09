<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Runner;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Style\StyleInterface;

interface InstallRunnerInterface
{
    /**
     * @param \Spryker\Install\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Install\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return void
     */
    public function run(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    );
}
