<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business;

use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Install\Business\InstallBusinessFactory getFactory()
 */
class InstallFacade extends AbstractFacade implements InstallFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentTransfer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $style
     *
     * @return void
     */
    public function runInstall(
        CommandLineArgumentContainer $commandLineArgumentTransfer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $style
    ): void {
        $this->getFactory()->createInstallRunner()->run($commandLineArgumentTransfer, $commandLineOptionContainer, $style);
    }
}
