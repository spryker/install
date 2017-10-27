<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Symfony\Component\Console\Style\StyleInterface;

class SetupFacade
{
    /**
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $style
    ) {
        return $this->getFactory()->createConfigurationBuilder()->buildConfiguration($commandLineArgumentContainer, $commandLineOptionContainer, $style);
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return \Spryker\Setup\Executable\ExecutableInterface
     */
    public function getExecutable(CommandInterface $command)
    {
        return $this->getFactory()->createExecutableFactory()->createExecutableFromCommand($command);
    }

    /**
     * @return \Spryker\Setup\SetupFactory
     */
    protected function getFactory()
    {
        return new SetupFactory();
    }
}
