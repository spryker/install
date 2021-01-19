<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Executable;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;

interface ExecutableFactoryInterface
{
    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return \Spryker\Zed\Install\Business\Executable\ExecutableInterface
     */
    public function createExecutableFromCommand(CommandInterface $command, ConfigurationInterface $configuration): ExecutableInterface;
}
