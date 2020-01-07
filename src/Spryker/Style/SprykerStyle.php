<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Style;

use Spryker\Install\Logger\InstallLoggerInterface;
use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Install\Stage\Section\SectionInterface;
use Spryker\Install\Stage\StageInterface;
use Spryker\Install\Timer\TimerInterface;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class SprykerStyle implements StyleInterface
{
    use InputHelper;
    use CursorHelper;

    public const MAX_LINE_LENGTH = 120;

    /**
     * @var \Symfony\Component\Console\Output\BufferedOutput
     */
    protected $bufferedOutput;

    /**
     * @var int
     */
    protected $lineLength;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Spryker\Install\Timer\TimerInterface
     */
    protected $timer;

    /**
     * @var \Spryker\Install\Logger\InstallLoggerInterface|null
     */
    protected $logger;

    /**
     * @var array
     */
    protected $outputBuffer = [];

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Spryker\Install\Timer\TimerInterface $timer
     * @param \Spryker\Install\Logger\InstallLoggerInterface|null $logger
     */
    public function __construct(InputInterface $input, OutputInterface $output, TimerInterface $timer, ?InstallLoggerInterface $logger)
    {
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        $width = (new Terminal())->getWidth() ?: static::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int)(DIRECTORY_SEPARATOR === '\\'), static::MAX_LINE_LENGTH);

        $this->input = $input;
        $this->output = $output;

        $this->timer = $timer;
        $this->logger = $logger;
    }

    /**
     * @param int $count
     *
     * @return void
     */
    public function newLine($count = 1)
    {
        $this->write(str_repeat(PHP_EOL, $count));
    }

    /**
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startInstall(StageInterface $stage)
    {
        $this->timer->start($stage);
        $message = sprintf('Install <fg=green>%s</> environment', $stage->getName());
        $messageLengthWithoutDecoration = Helper::strlenWithoutDecoration($this->output->getFormatter(), $message);
        $message = $message . str_pad(' ', $this->lineLength - $messageLengthWithoutDecoration);

        $this->writeln([
            str_repeat('=', $this->lineLength),
            $message,
            str_repeat('=', $this->lineLength),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endInstall(StageInterface $stage)
    {
        $message = sprintf('Install <fg=green>%s</> finished in <fg=green>%ss</>', $stage->getName(), $this->timer->end($stage));
        $this->writeln($message);
    }

    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section)
    {
        $this->timer->start($section);
        $message = sprintf('<bg=green;options=bold> Section %s</>', $section->getName());
        $messageLengthWithoutDecoration = Helper::strlenWithoutDecoration($this->output->getFormatter(), $message);
        $messageLength = $this->lineLength - $messageLengthWithoutDecoration;

        $this->writeln([
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
            sprintf('<bg=green;options=bold>%s%s</>', $message, str_pad(' ', $messageLength)),
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section)
    {
        $this->newLine();

        if ($this->output->isVerbose()) {
            $message = sprintf('Section <fg=green>%s</> finished in <fg=green>%ss</>', $section->getName(), $this->timer->end($section));
            $this->writeln($message);
            $this->writeln(str_repeat('=', $this->lineLength));
            $this->newLine(3);
        }
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, $store = null)
    {
        $this->timer->start($command);
        $message = $this->getStartCommandMessage($command, $store);
        if ($this->output->getVerbosity() === static::VERBOSITY_NORMAL) {
            $message .= sprintf(' <fg=magenta>(In progress...)</>');
        }

        if ($this->output->isVerbose()) {
            $this->writeln($message);
            $this->writeln(str_repeat('-', $this->lineLength));
            $this->newLine();

            return;
        }

        $this->writeln($message);
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return string
     */
    protected function getStartCommandMessage(CommandInterface $command, $store = null)
    {
        $commandInfo = sprintf('Command <fg=green>%s</>', $command->getName());
        $storeInfo = ($store) ?  sprintf(' for <info>%s</info> store', $store) : '';
        $executedInfo = sprintf(' <fg=yellow>[%s]</>', $command->getExecutable());

        return $commandInfo . $storeInfo . $executedInfo;
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param string|null $store
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, $exitCode, $store = null)
    {
        if ($this->output->isVeryVerbose()) {
            $this->newLine();
        }

        if ($this->output->isVerbose()) {
            $this->writeln($this->getVerboseCommandEndMessage($command, $exitCode));
            $this->newLine();

            return;
        }

        $this->endCommandOutputIfNormalOutput($command, $store);
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    protected function endCommandOutputIfNormalOutput(CommandInterface $command, $store = null)
    {
        if ($this->output->getVerbosity() === static::VERBOSITY_NORMAL) {
            $message = $this->getStartCommandMessage($command, $store);
            $message .= sprintf(' <fg=green>(%ss)</>', $this->timer->end($command));

            $this->moveLineUp();
            $this->moveCursorToBeginOfLine();
            $this->eraseLine();

            $this->writeln($message);
        }
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     *
     * @return string
     */
    protected function getVerboseCommandEndMessage(CommandInterface $command, $exitCode)
    {
        return sprintf(
            '<fg=green>//</> Command <fg=green>%s</> finished in <fg=green>%ss</>, exit code <fg=%s>%s</>',
            $command->getName(),
            $this->timer->end($command),
            ($exitCode !== 0) ? 'red' : 'green',
            $exitCode
        );
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    public function dryRunCommand(CommandInterface $command)
    {
        $this->newLine();
        $this->write(' // Dry-run: ' . $command->getName());
        $this->newLine(3);
    }

    /**
     * @param string $output
     *
     * @return void
     */
    public function innerCommand($output)
    {
        if ($this->output->isVeryVerbose()) {
            $this->write($output);
        }

        if (!$this->output->isVeryVerbose()) {
            $this->outputBuffer[] = $output;
        }
    }

    /**
     * @return void
     */
    public function flushBuffer()
    {
        foreach ($this->outputBuffer as $output) {
            $this->write($output);
        }
    }

    /**
     * @param string $output
     *
     * @return void
     */
    public function note($output)
    {
        if ($this->output->isVeryVerbose()) {
            $this->write(' // ' . $output);
        }
    }

    /**
     * @param array|string $messages
     * @param int $options
     *
     * @return void
     */
    public function write($messages, $options = 0)
    {
        $this->log($messages);
        $this->output->write($messages, false, $options);
    }

    /**
     * @param array|string $messages
     * @param int $options
     *
     * @return void
     */
    protected function writeln($messages, $options = 0)
    {
        $this->log($messages);
        $this->output->writeln($messages, $options);
    }

    /**
     * @param string|array $message
     *
     * @return void
     */
    protected function log($message): void
    {
        if ($this->logger !== null) {
            $this->logger->log($message);
        }
    }
}
