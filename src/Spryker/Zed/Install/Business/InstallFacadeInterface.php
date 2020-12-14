<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business;

use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;

interface InstallFacadeInterface
{
    /**
     * Specification:
     * - Starts the installation process.
     *
     * @api
     *
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $style
     *
     * @return void
     */
    public function runInstall(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $style
    ): void;
}
