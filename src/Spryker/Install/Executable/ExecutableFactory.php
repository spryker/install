<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Executable;

use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Executable\CommandLine\CommandLineExecutable;
use Spryker\Install\Stage\Section\Command\CommandInterface;

class ExecutableFactory
{
    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Install\Executable\ExecutableInterface
     */
    public function createExecutableFromCommand(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): ExecutableInterface {
        return $this->createCommandLineExecutable($command, $configuration);
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Install\Executable\CommandLine\CommandLineExecutable
     */
    protected function createCommandLineExecutable(
        CommandInterface $command,
        ConfigurationInterface $configuration
    ): CommandLineExecutable {
        return new CommandLineExecutable($command, $configuration);
    }
}
