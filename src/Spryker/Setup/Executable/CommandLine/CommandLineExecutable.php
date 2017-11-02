<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable\CommandLine;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Exception\SetupException;
use Spryker\Setup\Executable\ExecutableInterface;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Process\Process;

class CommandLineExecutable implements ExecutableInterface
{
    /**
     * @var \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $this->command = $command;
        $this->configuration = $configuration;
    }

    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @return int
     */
    public function execute(StyleInterface $style)
    {
        $process = new Process($this->command->getExecutable(), SPRYKER_ROOT, null, null, 600);
        $process->run();

        foreach ($process as $buffer) {
            echo $buffer;
        }

        if (!$process->isSuccessful()) {
            $this->shouldContinueAfterException();
        }

        return ($process->getExitCode() === null) ? static::CODE_SUCCESS : $process->getExitCode();
    }

    /**
     * @throws \Spryker\Setup\Exception\SetupException
     *
     * @return void
     */
    protected function shouldContinueAfterException()
    {
        if (!$this->configuration->shouldAskBeforeContinueAfterException()) {
            return;
        }

        $question = sprintf('Command <fg=green>%s</> failed! Continue with setup?', $this->command->getName());
        if (!$this->configuration->getOutput()->confirm($question)) {
            throw new SetupException('Aborted setup...');
        }
    }
}
