<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Executable;

use Spryker\Style\StyleInterface;

interface ExecutableInterface
{
    public const CODE_SUCCESS = 0;
    public const CODE_ERROR = 1;

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output): int;
}
