<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable;

use Symfony\Component\Console\Style\StyleInterface;

interface ExecutableInterface
{
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;

    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output);
}
