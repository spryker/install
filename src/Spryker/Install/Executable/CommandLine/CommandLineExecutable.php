<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Executable\CommandLine;

use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Exception\InstallException;
use Spryker\Install\Executable\ExecutableInterface;
use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Style\StyleInterface;
use Symfony\Component\Process\Process;

class CommandLineExecutable implements ExecutableInterface
{
    /**
     * @var \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Install\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
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
        $process = new Process($this->command->getExecutable(), SPRYKER_ROOT, getenv(), null, 600);
        $process->inheritEnvironmentVariables(true);
        $process->start();

        foreach ($process as $buffer) {
            $output->innerCommand($buffer);
        }

        if (!$process->isSuccessful()) {
            $this->abortInstallIfNotAllowedToContinue($output);
        }

        return ($process->getExitCode() === null) ? static::CODE_SUCCESS : $process->getExitCode();
    }

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @throws \Spryker\Install\Exception\InstallException
     *
     * @return void
     */
    protected function abortInstallIfNotAllowedToContinue(StyleInterface $output)
    {
        if ($this->command->breakOnFailure() || !$this->askToContinue($output)) {
            throw new InstallException('Aborted install...');
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
        $question = sprintf('Command <fg=yellow>%s</> failed! Continue with install?', $this->command->getName());

        return $output->confirm($question, true);
    }
}
