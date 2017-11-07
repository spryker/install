<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Style;

use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\SectionInterface;
use Spryker\Setup\Stage\StageInterface;
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        $width = (new Terminal())->getWidth() ?: static::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int)(DIRECTORY_SEPARATOR === '\\'), static::MAX_LINE_LENGTH);

        $this->input = $input;
        $this->output = $output;
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
        $message = sprintf('Setup <fg=green>%s</> finished in <fg=green>%ss</>', $stage->getName(), 23.34);
        $this->writeln($message);
    }

    /**
     * {@inheritdoc}
     */
    public function startSection(SectionInterface $section)
    {
        $message = sprintf('<bg=green;options=bold> Section %s</>', $section->getName());
        $messageLengthWithoutDecoration = Helper::strlenWithoutDecoration($this->getFormatter(), $message);
        $messageLength = $this->lineLength - $messageLengthWithoutDecoration;

        $this->writeln([
            sprintf('<bg=green>%s</>', str_repeat(' ', $this->lineLength)),
            sprintf('<bg=green;options=bold> Section %s%s</>', $section->getName(), str_pad(' ', $messageLength)),
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
            $message = sprintf('Section <fg=green>%s</> finished in <fg=green>%ss</>', $section->getName(), 23.34);
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
        $commandInfo = sprintf('Command <fg=green>%s</>', $command->getName());
        $storeInfo = ($store) ?  sprintf(' for <info>%s</info> store', $store) : '';
        $executedInfo = sprintf(' <fg=yellow>[%s]</>', $command->getExecutable());

        $message = $commandInfo . $storeInfo . $executedInfo;

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
     * @param int $exitCode
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, $exitCode)
    {
        $exitCodeColor = ($exitCode !== 0) ? 'red' : 'green';
        $message = sprintf('<fg=green>//</> Command <fg=green>%s</> finished in <fg=green>%ss</>, exit code <fg=%s>%s</>', $command->getName(), 2.12, $exitCodeColor, $exitCode);

        if ($this->output->isVeryVerbose()) {
            $this->newLine();
        }

        if ($this->output->isVerbose()) {
            $this->writeln($message);
            $this->newLine();
        }
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
}
