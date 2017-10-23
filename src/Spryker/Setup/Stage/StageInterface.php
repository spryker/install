<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage;

interface StageInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return \Spryker\Setup\Stage\Section\SectionInterface[]
     */
    public function getSections();
}
