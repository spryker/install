<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Symfony\Component\Console\Style\SymfonyStyle;

interface ConfigurationBuilderInterface
{
    /**
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     *
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        SymfonyStyle $style
    );
}
