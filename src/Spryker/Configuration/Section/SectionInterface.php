<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Section;

interface SectionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return \Spryker\Configuration\Command\CommandInterface[]
     */
    public function getCommands();
}
