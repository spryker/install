<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder\Section\Command;

use Spryker\Setup\Stage\Section\Command\CommandInterface;

interface CommandBuilderInterface
{
    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function buildCommand(string $name, array $definition): CommandInterface;
}
