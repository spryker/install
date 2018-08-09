<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Runner\Section\Command;

use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Stage\Section\Command\CommandInterface;

interface CommandRunnerInterface
{
    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Install\Exception\InstallException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration);
}
