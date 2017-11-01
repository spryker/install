<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner\Section;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Setup\Stage\Section\SectionInterface;

class SectionRunner implements SectionRunnerInterface
{
    /**
     * @var \Spryker\Setup\Runner\Section\Command\CommandRunnerInterface
     */
    protected $commandRunner;

    /**
     * @param \Spryker\Setup\Runner\Section\Command\CommandRunnerInterface $commandRunner
     */
    public function __construct(CommandRunnerInterface $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function run(SectionInterface $section, ConfigurationInterface $configuration)
    {
        $configuration->getOutput()->section($section->getName());

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
    }
}
