<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Stage;

interface StageInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return \Spryker\Configuration\Section\SectionInterface[]
     */
    public function getSections();
}
