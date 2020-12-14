<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Section;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunnerInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;

class SectionRunner implements SectionRunnerInterface
{
    /**
     * @var \Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunnerInterface
     */
    protected $commandRunner;

    /**
     * @param \Spryker\Zed\Install\Business\Runner\Section\Command\CommandRunnerInterface $commandRunner
     */
    public function __construct(CommandRunnerInterface $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
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
