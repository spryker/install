<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    public const DEFAULT_TIMEOUT_IN_SECONDS = 600;

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
        $process = $this->buildProcess();
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
     * @return \Symfony\Component\Process\Process
     */
    protected function buildProcess()
    {
        return new Process(
            $this->command->getExecutable(),
            SPRYKER_ROOT,
            getenv(),
            null,
            $this->getProcessTimeout()
        );
    }

    /**
     * @return int
     */
    protected function getProcessTimeout()
    {
        if ($this->command->hasTimeout()) {
            return $this->command->getTimeout();
        }

        return $this->configuration->getCommandTimeout() ?: static::DEFAULT_TIMEOUT_IN_SECONDS;
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
            $output->flushBuffer();

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
