<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Executable;

use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Executable\CommandLine\CommandLineExecutable;
use Spryker\Deploy\Stage\Section\Command\CommandInterface;

class ExecutableFactory
{
    /**
     * @param \Spryker\Deploy\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Deploy\Executable\ExecutableInterface
     */
    public function createExecutableFromCommand(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): ExecutableInterface {
        return $this->createCommandLineExecutable($command, $configuration);
    }

    /**
     * @param \Spryker\Deploy\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Deploy\Executable\CommandLine\CommandLineExecutable
     */
    protected function createCommandLineExecutable(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): CommandLineExecutable {
        return new CommandLineExecutable($command, $configuration);
    }
}
