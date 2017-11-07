<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Style;

use Codeception\Test\Unit;
use Spryker\Style\StyleInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Style
 * @group SprykerStyleVerboseOutputTest
 * Add your own group annotations below this line
 */
class SprykerStyleVerboseOutputTest extends Unit
{
    const STORE = 'DE';

    /**
     * @var \SprykerTest\StyleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testEndSectionVerboseOutput()
    {
        $section = $this->tester->getSection();
        $sprykerStyle = $this->tester->getSprykerStyle(StyleInterface::VERBOSITY_VERBOSE);
        $sprykerStyle->endSection($section);

        $text = sprintf('Section %s finished in 23.34s', $section->getName());

        $block = [
            $this->tester->newLine(),
            $text . $this->tester->newLine(),
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testStartCommandVerboseOutputWithStore()
    {
        $command = $this->tester->getCommand();

        $sprykerStyle = $this->tester->getSprykerStyle(StyleInterface::VERBOSITY_VERBOSE);
        $sprykerStyle->startCommand($command, static::STORE);

        $text = sprintf('Command %s for %s store [%s]', $command->getName(), static::STORE, $command->getExecutable());

        $block = [
            $text . $this->tester->newLine(),
            $this->tester->repeat('-') . $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testEndCommandVerboseOutput()
    {
        $command = $this->tester->getCommand();

        $sprykerStyle = $this->tester->getSprykerStyle(StyleInterface::VERBOSITY_VERBOSE);
        $sprykerStyle->endCommand($command, 0);

        $text = sprintf('// Command %s finished in 2.12s, exit code 0', $command->getName());

        $block = [
            $text,
            $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testEndCommandVeryVerboseOutput()
    {
        $command = $this->tester->getCommand();

        $sprykerStyle = $this->tester->getSprykerStyle(StyleInterface::VERBOSITY_VERY_VERBOSE);
        $sprykerStyle->endCommand($command, 0);

        $text = sprintf('// Command %s finished in 2.12s, exit code 0', $command->getName());

        $block = [
            $this->tester->newLine(),
            $text,
            $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testFullOutput()
    {
        $this->tester->fakeFullSetupRun(StyleInterface::VERBOSITY_VERBOSE);

        $block = [
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getStageStartText()),
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getSectionStartText()),
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandStartTextWithoutStore() . $this->tester->newLine(),
            $this->tester->repeat('-') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandEndText(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandStartTextWithoutStore() . $this->tester->newLine(),
            $this->tester->repeat('-') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandEndText(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getSectionEndText() . $this->tester->newLine(),
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getStageEndText() . $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }
}
