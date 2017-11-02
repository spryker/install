<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Executable\Collection\ExecutableCollection;
use Spryker\Setup\Executable\CommandLine\CommandLineExecutable;
use Spryker\Setup\Executable\Composer\ComposerInstallExecutable;
use Spryker\Setup\Stage\Section\Command\CommandInterface;

class ExecutableFactory
{
    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Setup\Executable\ExecutableInterface
     */
    public function createExecutableFromCommand(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $executableCollection = $this->createExecutableCollection();
        if ($executableCollection->hasExecutable($command->getExecutable())) {
            return $executableCollection->getExecutable($command->getExecutable());
        }

        return $this->createCommandLineExecutable($command, $configuration);
    }

    /**
     * @return \Spryker\Setup\Executable\Collection\ExecutableCollectionInterface
     */
    protected function createExecutableCollection()
    {
        $executableCollection = new ExecutableCollection();
        $executableCollection->addExecutable('composer', $this->createComposerInstallExecutable());

        return $executableCollection;
    }

    /**
     * @return \Spryker\Setup\Executable\Composer\ComposerInstallExecutable|\Spryker\Setup\Executable\ExecutableInterface
     */
    protected function createComposerInstallExecutable()
    {
        $composerInstallExecutable = new ComposerInstallExecutable();

        return $composerInstallExecutable;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Setup\Executable\CommandLine\CommandLineExecutable
     */
    protected function createCommandLineExecutable(CommandInterface $command, ConfigurationInterface $configuration)
    {
        return new CommandLineExecutable($command, $configuration);
    }
}
