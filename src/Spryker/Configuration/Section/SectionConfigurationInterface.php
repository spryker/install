<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Section;

use Spryker\Configuration\Command\CommandInterface;

interface SectionConfigurationInterface
{
    /**
     * @param \Spryker\Configuration\Command\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command);
}
