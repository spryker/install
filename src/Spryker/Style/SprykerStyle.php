<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Style;

use Spryker\Setup\Logger\SetupLoggerInterface;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\SectionInterface;
use Spryker\Setup\Stage\StageInterface;
use Spryker\Setup\Timer\TimerInterface;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Terminal;

class SprykerStyle implements StyleInterface
{
    const MAX_LINE_LENGTH = 120;

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
     * @var \Symfony\Component\Console\Helper\SymfonyQuestionHelper
     */
    protected $questionHelper;

    /**
     * @var \Spryker\Setup\Timer\TimerInterface
     */
    protected $timer;

    /**
     * @var \Spryker\Setup\Logger\SetupLoggerInterface|null
     */
    protected $logger;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Spryker\Setup\Timer\TimerInterface $timer
     * @param \Spryker\Setup\Logger\SetupLoggerInterface|null $logger
     */
    public function __construct(InputInterface $input, OutputInterface $output, TimerInterface $timer, SetupLoggerInterface $logger = null)
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
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startSetup(StageInterface $stage)
    {
        $this->timer->start($stage);
        $message = sprintf('Setup for <fg=green>%s</> environment', $stage->getName());
        $messageLengthWithoutDecoration = Helper::strlenWithoutDecoration($this->getFormatter(), $message);
        $message = $message . str_pad(' ', $this->lineLength - $messageLengthWithoutDecoration);

        $this->writeln([
            str_repeat('=', $this->lineLength),
            $message,
            str_repeat('=', $this->lineLength),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endSetup(StageInterface $stage)
    {
        $message = sprintf('Setup <fg=green>%s</> finished in <fg=green>%ss</>', $stage->getName(), $this->timer->end($stage));
        $this->writeln($message);
    }

    /**
     * {@inheritdoc}
     */
    public function startSection(SectionInterface $section)
    {
        $this->timer->start($section);
        $message = sprintf('<bg=green;options=bold> Section %s</>', $section->getName());
        $messageLengthWithoutDecoration = Helper::strlenWithoutDecoration($this->getFormatter(), $message);
        $messageLength = $this->lineLength - $messageLengthWithoutDecoration;

        $this->writeln([
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
            sprintf('<bg=green;options=bold>%s%s</>', $message, str_pad(' ', $messageLength)),
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
        ]);

        $this->newLine();
    }

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param null|string $store
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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param null|string $store
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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param null|string $store
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

        if ($this->output->getVerbosity() === static::VERBOSITY_NORMAL) {
            $message = $this->getStartCommandMessage($command, $store);
            $message .= sprintf(' <fg=green>(%s)</>', $this->timer->end($command));

            $this->moveLineUp();
            $this->moveCursorToBeginOfLine();
            $this->eraseLine();

            $this->writeln($message);
        }
    }

    /**
     * @param int $count
     *
     * @return void
     */
    protected function moveLineUp($count = 1)
    {
        $output = sprintf("\x1B[%sA", $count);
        $this->write($output);
    }

    /**
     * @return void
     */
    protected function moveCursorToBeginOfLine()
    {
        $this->write("\x0D");
    }

    /**
     * @return void
     */
    protected function eraseLine()
    {
        $this->write("\x1B[2K");
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
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
     * @param string $question
     * @param bool $default
     *
     * @return string
     */
    public function confirm($question, $default = true)
    {
        return $this->askQuestion(new ConfirmationQuestion($question, $default));
    }

    /**
     * @param string $question
     * @param array $choices
     * @param string|int|null $default
     *
     * @return bool|mixed|null|string
     */
    public function choice($question, array $choices, $default = null)
    {
        if ($default) {
            $values = array_flip($choices);
            $default = $values[$default];
        }
        $question = new ChoiceQuestion($question, $choices, $default);
        $question->setMultiselect(true);

        return $this->askQuestion($question);
    }

    /**
     * @param \Symfony\Component\Console\Question\Question $question
     *
     * @return string
     */
    protected function askQuestion(Question $question)
    {
        if (!$this->questionHelper) {
            $this->questionHelper = new SymfonyQuestionHelper();
        }

        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * @return \Symfony\Component\Console\Formatter\OutputFormatterInterface
     */
    protected function getFormatter()
    {
        return $this->output->getFormatter();
    }

    /**
     * @param array|string $messages
     *
     * @return void
     */
    protected function log($messages)
    {
        if ($this->logger) {
            $this->logger->log(implode((array)$messages));
        }
    }
}
