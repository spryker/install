<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Executable\CommandLine;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Exception\InstallException;
use Spryker\Zed\Install\Business\Executable\ExecutableInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Communication\Style\StyleInterface;
use Symfony\Component\Process\Process;

class CommandLineExecutable implements ExecutableInterface
{
    public const DEFAULT_TIMEOUT_IN_SECONDS = 600;

    /**
     * @var \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface
     */
    protected $command;

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $this->command = $command;
        $this->configuration = $configuration;
    }

    /**
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return int
     */
    public function execute(StyleInterface $output): int
    {
        $process = $this->buildProcess();
        if (method_exists($process, 'inheritEnvironmentVariables')) {
            $process->inheritEnvironmentVariables(true);
        }
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
        if (method_exists(Process::class, 'fromShellCommandline')) {
            return Process::fromShellCommandline($this->command->getExecutable(), SPRYKER_ROOT, getenv(), null, $this->getProcessTimeout());
        }

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
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @throws \Spryker\Zed\Install\Business\Exception\InstallException
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
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
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
