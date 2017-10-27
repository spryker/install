<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable\CommandLine;

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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
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

        foreach ($process as $type => $buffer) {
            if ($type === $process::OUT) {
                echo $buffer;
            }
        }

        return ($process->getExitCode() === null) ? static::CODE_SUCCESS : $process->getExitCode();
    }
}
