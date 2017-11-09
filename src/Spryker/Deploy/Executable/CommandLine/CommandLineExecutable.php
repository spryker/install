<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Executable\CommandLine;

use Spryker\Deploy\Configuration\ConfigurationInterface;
use Spryker\Deploy\Exception\DeployException;
use Spryker\Deploy\Executable\ExecutableInterface;
use Spryker\Deploy\Stage\Section\Command\CommandInterface;
use Spryker\Style\StyleInterface;
use Symfony\Component\Process\Process;

class CommandLineExecutable implements ExecutableInterface
{
    /**
     * @var \Spryker\Deploy\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Deploy\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Deploy\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $this->command = $command;
        $this->configuration = $configuration;
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output): int
    {
        $process = new Process($this->command->getExecutable(), SPRYKER_ROOT, null, null, 600);
        $process->start();

        foreach ($process as $buffer) {
            $output->innerCommand($buffer);
        }

        if (!$process->isSuccessful()) {
            $this->abortDeployIfNotAllowedToContinue($output);
        }

        return ($process->getExitCode() === null) ? static::CODE_SUCCESS : $process->getExitCode();
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @throws \Spryker\Deploy\Exception\DeployException
     *
     * @return void
     */
    protected function abortDeployIfNotAllowedToContinue(StyleInterface $output)
    {
        if ($this->command->breakOnFailure() || !$this->askToContinue($output)) {
            throw new DeployException('Aborted deploy...');
        }
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return bool
     */
    protected function askToContinue(StyleInterface $output): bool
    {
        if (!$this->configuration->shouldAskBeforeContinueAfterException()) {
            return true;
        }

        $output->newLine();
        $question = sprintf('Command <fg=yellow>%s</> failed! Continue with deploy?', $this->command->getName());

        return $output->confirm($question, true);
    }
}
