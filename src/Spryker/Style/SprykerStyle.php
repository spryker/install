<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Style;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

class SprykerStyle extends SymfonyStyle
{
    /**
     * @var \Symfony\Component\Console\Output\BufferedOutput
     */
    protected $bufferedOutput;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var int
     */
    private $lineLength;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->bufferedOutput = new BufferedOutput($output->getVerbosity(), false, clone $output->getFormatter());
        // Windows cmd wraps lines as soon as the terminal width is reached, whether there are following chars or not.
        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int)(DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);

        parent::__construct($input, $output);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function title($message)
    {
        $message = $message . str_pad(' ', $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $message));

        $this->newLine();
        $this->writeln([
            sprintf('<comment>%s</>', str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
            sprintf('<comment>%s</>', OutputFormatter::escapeTrailingBackslash($message)),
            sprintf('<comment>%s</>', str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ]);
        $this->newLine();
    }

    /**
     * {@inheritdoc}
     */
    public function section($message)
    {
        $message = sprintf('Section: <fg=green>%s</>', $message);
        $messageLength = $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $message);
        $message = $message . str_pad(' ', $messageLength);

        $this->newLine();
        $this->writeln([
            sprintf('<comment>%s</>', str_repeat('*', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
            sprintf('<comment>%s</>', str_repeat(' ', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
            sprintf('<comment>%s</>', OutputFormatter::escapeTrailingBackslash($message)),
            sprintf('<comment>%s</>', str_repeat(' ', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
            sprintf('<comment>%s</>', str_repeat('*', Helper::strlenWithoutDecoration($this->getFormatter(), $message))),
        ]);
        $this->newLine();
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function text($message)
    {
        $messageLength = $this->lineLength - Helper::strlenWithoutDecoration($this->getFormatter(), $message);
        $message = $message . str_pad(' ', $messageLength);

        $this->autoPrependText();
        $this->writeln([
            $message,
            str_repeat('=', Helper::strlenWithoutDecoration($this->getFormatter(), $message)),
        ]);
    }

    /**
     * @return void
     */
    protected function autoPrependText()
    {
        $fetched = $this->bufferedOutput->fetch();
        if ("\n" !== substr($fetched, -1)) {
            $this->newLine();
        }
    }
}
