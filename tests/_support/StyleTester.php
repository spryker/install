<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest;

use Codeception\Actor;
use Codeception\Scenario;
use Spryker\Setup\Stage\Section\Command\Command;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\Section;
use Spryker\Setup\Stage\Section\SectionInterface;
use Spryker\Setup\Stage\Stage;
use Spryker\Setup\Stage\StageInterface;
use Spryker\Setup\Timer\TimerInterface;
use Spryker\Style\SprykerStyle;
use Spryker\Style\StyleInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Terminal;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class StyleTester extends Actor
{
    const STAGE_NAME = 'Stage name';
    const SECTION_NAME = 'Section Name';
    const COMMAND_NAME = 'Command Name';
    const STORE = 'DE';

    use _generated\StyleTesterActions;

    /**
     * @var \Symfony\Component\Console\Output\StreamOutput
     */
    protected $output;

    /**
     * @var int
     */
    protected $lineLength;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $width = (new Terminal())->getWidth() ?: SprykerStyle::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int)(DIRECTORY_SEPARATOR === '\\'), SprykerStyle::MAX_LINE_LENGTH);
    }

    /**
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function getStage()
    {
        $stage = new Stage(static::STAGE_NAME);

        return $stage;
    }

    /**
     * @return \Spryker\Setup\Stage\Section\SectionInterface
     */
    public function getSection()
    {
        $section = new Section(static::STAGE_NAME);

        return $section;
    }

    /**
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function getCommand()
    {
        $command = new Command(static::COMMAND_NAME);
        $command->setExecutable('ls -la');

        return $command;
    }

    /**
     * @return int
     */
    public function getLineLength()
    {
        return $this->lineLength;
    }

    /**
     * @param string $char
     * @param null|int $length
     *
     * @return string
     */
    public function repeat($char, $length = null)
    {
        return str_repeat($char, ($length === null) ? $this->lineLength : $length);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function fullLineLength($text)
    {
        return $text . $this->repeat(' ', $this->lineLength - strlen($text)) . $this->newLine();
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function writeln($text)
    {
        return $text . $this->newLine();
    }

    /**
     * @param int $count
     *
     * @return string
     */
    public function newLine($count = 1)
    {
        return str_repeat(PHP_EOL, $count);
    }

    /**
     * @param int $verbosity
     *
     * @return \Spryker\Style\SprykerStyle
     */
    public function getSprykerStyle($verbosity = StyleInterface::VERBOSITY_NORMAL)
    {
        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        $this->output->setDecorated(false);
        $this->output->setVerbosity($verbosity);

        return new SprykerStyle(new ArgvInput(), $this->output, $this->getTimer());
    }

    /**
     * @return \Spryker\Setup\Timer\TimerInterface
     */
    protected function getTimer()
    {
        return new class implements TimerInterface {

            /**
             * @param object $object
             *
             * @return $this
             */
            public function start($object)
            {
                return new self();
            }

            /**
             * @param object $object
             *
             * @return float
             */
            public function end($object)
            {
                if ($object instanceof StageInterface) {
                    return 123.45;
                }
                if ($object instanceof SectionInterface) {
                    return 12.34;
                }

                return 1.23;
            }
        };
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        rewind($this->output->getStream());

        return stream_get_contents($this->output->getStream());
    }

    /**
     * @param int $verbosity
     *
     * @return void
     */
    public function fakeFullSetupRun($verbosity = StyleInterface::VERBOSITY_NORMAL)
    {
        $stage = $this->getStage();
        $section = $this->getSection();
        $command = $this->getCommand();

        $sprykerStyle = $this->getSprykerStyle($verbosity);

        $sprykerStyle->startSetup($stage);
        $sprykerStyle->startSection($section);
        $this->fakeCommandRun($sprykerStyle, $command);
        $this->fakeCommandRun($sprykerStyle, $command);
        $sprykerStyle->endSection($section);
        $sprykerStyle->endSetup($stage);
    }

    /**
     * @param \Spryker\Style\StyleInterface $sprykerStyle
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    protected function fakeCommandRun(StyleInterface $sprykerStyle, CommandInterface $command)
    {
        $sprykerStyle->startCommand($command);
        $sprykerStyle->innerCommand("foo\n");
        $sprykerStyle->innerCommand("bar\n");
        $sprykerStyle->innerCommand("baz\n");
        $sprykerStyle->endCommand($command, 0);
    }

    /**
     * @return string
     */
    public function getStore()
    {
        return static::STORE;
    }

    /**
     * @return string
     */
    public function getStageStartText()
    {
        return sprintf('Setup for %s environment', $this->getStage()->getName());
    }

    /**
     * @return string
     */
    public function getStageEndText()
    {
        return sprintf('Setup %s finished in %ss', $this->getStage()->getName(), $this->getTimer()->end($this->getStage()));
    }

    /**
     * @return string
     */
    public function getSectionStartText()
    {
        return sprintf(' Section %s', $this->getSection()->getName());
    }

    /**
     * @return string
     */
    public function getSectionEndText()
    {
        return sprintf('Section %s finished in %ss', $this->getSection()->getName(), $this->getTimer()->end($this->getSection()));
    }

    /**
     * @return string
     */
    public function getCommandStartTextWithStore()
    {
        $command = $this->getCommand();

        return sprintf('Command %s for %s store [%s]', $command->getName(), static::STORE, $command->getExecutable());
    }

    /**
     * @return string
     */
    public function getCommandStartTextWithoutStore()
    {
        $command = $this->getCommand();

        return sprintf('Command %s [%s]', $command->getName(), $command->getExecutable());
    }

    /**
     * @return string
     */
    public function getCommandEndText()
    {
        $command = $this->getCommand();

        return sprintf('// Command %s finished in %ss, exit code 0', $command->getName(), $this->getTimer()->end($this->getCommand()));
    }
}
