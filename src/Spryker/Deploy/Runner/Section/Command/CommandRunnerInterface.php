<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner\Section\Command;

use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Stage\Section\Command\CommandInterface;

interface CommandRunnerInterface
{
    /**
     * @param \Spryker\Deploy\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Deploy\Exception\DeployException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration);
}
