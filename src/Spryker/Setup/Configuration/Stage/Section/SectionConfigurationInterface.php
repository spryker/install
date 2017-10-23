<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Stage\Section;

use Spryker\Setup\Stage\Section\Command\CommandInterface;

interface SectionConfigurationInterface
{
    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command);
}
