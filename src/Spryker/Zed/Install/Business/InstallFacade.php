<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function runInstall(InputInterface $input, OutputInterface $output): void
    {
        $this->getFactory()->createInstallRunner()->run($input, $output);
    }
}
