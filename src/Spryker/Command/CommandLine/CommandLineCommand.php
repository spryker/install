<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Command\CommandLine;

use Spryker\Command\CommandInterface;
use Spryker\Configuration\Command\CommandInterface as ConfigurationCommandInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CommandLineCommand implements CommandInterface
{
    /**
     * @var \Spryker\Configuration\Command\CommandInterface
     */
    protected $command;

    /**
     * @param \Spryker\Configuration\Command\CommandInterface $command
     */
    public function __construct(ConfigurationCommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     *
     * @return int
     */
    public function execute(StyleInterface $style)
    {
        $style->section(sprintf('Execute command line command: <info>%s</info>', $this->command->getName()));
        $style->note(sprintf('CLI call: %s', $this->command->getExecutable()));

        $process = new Process($this->command->getExecutable(), SPRYKER_ROOT, null, null, 600);
        $process->start();

        foreach ($process as $type => $buffer) {
            if ($type === $process::OUT) {
                echo $buffer;
            }
        }

//        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
//        }

        $style->newLine(1);

        if ($process->isSuccessful()) {
            $style->success('CLI call executed.');
        }

        $exitCode = $process->getExitCode();

        if ($exitCode === null) {
            return static::CODE_SUCCESS;
        }

        return $exitCode;
    }
}
