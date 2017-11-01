<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Symfony\Component\Console\Style\StyleInterface;

class SetupFacade
{
    /**
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @return void
     */
    public function runSetup(CommandLineArgumentContainer $commandLineArgumentContainer, CommandLineOptionContainer $commandLineOptionContainer, StyleInterface $style)
    {
        $this->getFactory()->createSetupRunner()->run($commandLineArgumentContainer, $commandLineOptionContainer, $style);
    }

    /**
     * @return \Spryker\Setup\SetupFactory
     */
    protected function getFactory()
    {
        return new SetupFactory();
    }
}
