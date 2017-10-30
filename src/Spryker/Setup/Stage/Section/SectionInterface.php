<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section;

interface SectionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isExcluded();

    /**
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface[]
     */
    public function getCommands();

    /**
     * @param string $commandName
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function getCommand($commandName);
}
