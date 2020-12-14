<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Executable;

use Spryker\Zed\Install\Communication\Style\StyleInterface;

interface ExecutableInterface
{
    public const CODE_SUCCESS = 0;
    public const CODE_ERROR = 1;

    /**
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output): int;
}
