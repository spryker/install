<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy;

use Spryker\Deploy\CommandLine\CommandLineArgumentContainer;
use Spryker\Deploy\CommandLine\CommandLineOptionContainer;
use Spryker\Style\StyleInterface;

class DeployFacade
{
    /**
     * @param \Spryker\Deploy\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Deploy\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $style
     *
     * @return void
     */
    public function runDeploy(CommandLineArgumentContainer $commandLineArgumentContainer, CommandLineOptionContainer $commandLineOptionContainer, StyleInterface $style)
    {
        $this->getFactory()->createDeployRunner()->run($commandLineArgumentContainer, $commandLineOptionContainer, $style);
    }

    /**
     * @return \Spryker\Deploy\DeployFactory
     */
    protected function getFactory(): DeployFactory
    {
        return new DeployFactory();
    }
}
