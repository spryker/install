<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console\Fixtures;

use Spryker\Setup\Executable\ExecutableInterface;
use Symfony\Component\Console\Style\StyleInterface;

class Executable implements ExecutableInterface
{
    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output)
    {
        $output->text('Executed CommandInterface');

        return 1;
    }
}
