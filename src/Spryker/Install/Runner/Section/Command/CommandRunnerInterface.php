<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
