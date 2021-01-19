<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Section\Command;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;

interface CommandRunnerInterface
{
    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Zed\Install\Business\Exception\InstallException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration): void;
}
