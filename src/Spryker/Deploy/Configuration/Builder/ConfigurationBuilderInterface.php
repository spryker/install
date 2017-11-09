<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Builder;

use Spryker\Deploy\CommandLine\CommandLineArgumentContainer;
use Spryker\Deploy\CommandLine\CommandLineOptionContainer;
use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Style\StyleInterface;

interface ConfigurationBuilderInterface
{
    /**
     * @param \Spryker\Deploy\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Deploy\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): ConfigurationInterface;
}
