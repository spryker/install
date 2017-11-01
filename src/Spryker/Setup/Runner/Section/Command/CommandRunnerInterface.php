<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner\Section\Command;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Stage\Section\Command\CommandInterface;

interface CommandRunnerInterface
{
    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Setup\Exception\SetupException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration);
}
