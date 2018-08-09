<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Configuration\Builder;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Style\StyleInterface;

interface ConfigurationBuilderInterface
{
    /**
     * @param \Spryker\Install\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Install\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): ConfigurationInterface;
}
