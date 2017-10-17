<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console\Fixtures;

use Spryker\Command\CommandInterface;
use Symfony\Component\Console\Style\StyleInterface;

class ExecutableCommand implements CommandInterface
{
    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return bool
     */
    public function execute(StyleInterface $output)
    {
        $output->text('Executed CommandInterface');

        return true;
    }
}
