<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Style\StyleInterface;

class InstallFacade
{
    /**
     * @param \Spryker\Install\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Install\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $style
     *
     * @return void
     */
    public function runInstall(CommandLineArgumentContainer $commandLineArgumentContainer, CommandLineOptionContainer $commandLineOptionContainer, StyleInterface $style)
    {
        $this->getFactory()->createInstallRunner()->run($commandLineArgumentContainer, $commandLineOptionContainer, $style);
    }

    /**
     * @return \Spryker\Install\InstallFactory
     */
    protected function getFactory(): InstallFactory
    {
        return new InstallFactory();
    }
}
