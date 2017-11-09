<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Executable\CommandLine\CommandLineExecutable;
use Spryker\Setup\Stage\Section\Command\CommandInterface;

class ExecutableFactory
{
    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Setup\Executable\ExecutableInterface
     */
    public function createExecutableFromCommand(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): ExecutableInterface {
        return $this->createCommandLineExecutable($command, $configuration);
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Setup\Executable\CommandLine\CommandLineExecutable
     */
    protected function createCommandLineExecutable(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): CommandLineExecutable {
        return new CommandLineExecutable($command, $configuration);
    }
}
