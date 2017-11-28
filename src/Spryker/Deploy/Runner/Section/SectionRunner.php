<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Runner\Section;

use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Deploy\Stage\Section\SectionInterface;

class SectionRunner implements SectionRunnerInterface
{
    /**
     * @var \Spryker\Deploy\Runner\Section\Command\CommandRunnerInterface
     */
    protected $commandRunner;

    /**
     * @param \Spryker\Deploy\Runner\Section\Command\CommandRunnerInterface $commandRunner
     */
    public function __construct(CommandRunnerInterface $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    /**
     * @param \Spryker\Deploy\Stage\Section\SectionInterface $section
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration)
    {
        $configuration->getOutput()->startSection($section);

        if ($section->hasPreCommand()) {
            $preCommand = $configuration->findCommand($section->getPreCommand());
            $this->commandRunner->run($preCommand, $configuration);
        }

        foreach ($section->getCommands() as $command) {
            $this->commandRunner->run($command, $configuration);
        }

        if ($section->hasPostCommand()) {
            $postCommand = $configuration->findCommand($section->getPostCommand());
            $this->commandRunner->run($postCommand, $configuration);
        }

        $configuration->getOutput()->endSection($section);
    }
}
