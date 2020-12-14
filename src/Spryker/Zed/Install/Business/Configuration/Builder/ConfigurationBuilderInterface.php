<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Builder;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;

interface ConfigurationBuilderInterface
{
    /**
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): ConfigurationInterface;
}
