<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Style;

use Spryker\Zed\Install\Business\Logger\InstallLoggerInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;
use Spryker\Zed\Install\Business\Stage\StageInterface;
use Spryker\Zed\Install\Business\Timer\TimerInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class SprykerStyle implements StyleInterface
{
    use InputHelper;
    use CursorHelper;

    /**
     * @var int
     */
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
     * @var \Spryker\Zed\Install\Business\Timer\TimerInterface
     */
    protected $timer;

    /**
     * @var \Spryker\Zed\Install\Business\Logger\InstallLoggerInterface|null
     */
    protected $logger;

    /**
     * @var array
     */
    protected $outputBuffer = [];

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Spryker\Zed\Install\Business\Timer\TimerInterface $timer
     * @param \Spryker\Zed\Install\Business\Logger\InstallLoggerInterface|null $logger
     */
    public function __construct(InputInterface $input, OutputInterface $output, TimerInterface $timer, ?InstallLoggerInterface $logger = null)
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
    public function newLine(int $count = 1): void
    {
        $this->write(str_repeat(PHP_EOL, $count));
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startInstall(StageInterface $stage): void
    {
        $this->timer->start($stage);
        $message = sprintf('Install <fg=green>%s</> environment', $stage->getName());
        $messageLengthWithoutDecoration = $this->strlenWithoutDecoration($this->output->getFormatter(), $message);
        $message = $message . str_pad(' ', $this->lineLength - $messageLengthWithoutDecoration);

        $this->writeln([
            str_repeat('=', $this->lineLength),
            $message,
            str_repeat('=', $this->lineLength),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endInstall(StageInterface $stage): void
    {
        $message = sprintf('Install <fg=green>%s</> finished in <fg=green>%ss</>', $stage->getName(), $this->timer->end($stage));
        $this->writeln($message);
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section): void
    {
        $this->timer->start($section);
        $message = sprintf('<bg=green;options=bold> Section %s</>', $section->getName());
        $messageLengthWithoutDecoration = $this->strlenWithoutDecoration($this->output->getFormatter(), $message);
        $messageLength = $this->lineLength - $messageLengthWithoutDecoration;

        $this->writeln([
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
            sprintf('<bg=green;options=bold>%s%s</>', $message, str_pad(' ', $messageLength)),
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, ?string $store = null): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return string
     */
    protected function getStartCommandMessage(CommandInterface $command, ?string $store = null): string
    {
        $commandInfo = sprintf('Command <fg=green>%s</>', $command->getName());
        $storeInfo = $store
            ? sprintf(' for <info>%s</info> store', $store)
            : '';
        $executedInfo = sprintf(' <fg=yellow>[%s]</>', $command->getExecutable());

        return $commandInfo . $storeInfo . $executedInfo;
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param string|null $store
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, int $exitCode, ?string $store = null): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    protected function endCommandOutputIfNormalOutput(CommandInterface $command, ?string $store = null): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     *
     * @return string
     */
    protected function getVerboseCommandEndMessage(CommandInterface $command, int $exitCode): string
    {
        return sprintf(
            '<fg=green>//</> Command <fg=green>%s</> finished in <fg=green>%ss</>, exit code <fg=%s>%s</>',
            $command->getName(),
            $this->timer->end($command),
            ($exitCode !== 0) ? 'red' : 'green',
            $exitCode,
        );
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    public function dryRunCommand(CommandInterface $command): void
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
    public function innerCommand(string $output): void
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
    public function flushBuffer(): void
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
    public function note(string $output): void
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
    public function write($messages, int $options = 0): void
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
    protected function writeln($messages, int $options = 0): void
    {
        $this->log($messages);
        $this->output->writeln($messages, $options);
    }

    /**
     * @param array|string $message
     *
     * @return void
     */
    protected function log($message): void
    {
        if ($this->logger !== null) {
            $this->logger->log($message);
        }
    }

    /**
     * @param \Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter
     * @param string|null $string
     *
     * @return int
     */
    protected function strlenWithoutDecoration(OutputFormatterInterface $formatter, ?string $string): int
    {
        return Helper::width(Helper::removeDecoration($formatter, $string));
    }
}
