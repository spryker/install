<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner;

use Spryker\Deploy\CommandLine\CommandLineArgumentContainer;
use Spryker\Deploy\CommandLine\CommandLineOptionContainer;
use Spryker\Style\StyleInterface;

interface DeployRunnerInterface
{
    /**
     * @param \Spryker\Deploy\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Deploy\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
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
