<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Command;

use Symfony\Component\Console\Style\StyleInterface;

interface CommandInterface
{
    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return bool
     */
    public function execute(StyleInterface $output);
}
