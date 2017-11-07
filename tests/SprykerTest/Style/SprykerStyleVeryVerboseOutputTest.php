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
 * @group SprykerStyleVeryVerboseOutputTest
 * Add your own group annotations below this line
 */
class SprykerStyleVeryVerboseOutputTest extends Unit
{
    const STORE = 'DE';

    /**
     * @var \SprykerTest\StyleTester
     */
    protected $tester;

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
        $this->tester->fakeFullSetupRun(StyleInterface::VERBOSITY_VERY_VERBOSE);

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
            'foo' . $this->tester->newLine(),
            'bar' . $this->tester->newLine(),
            'baz' . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandEndText(),
            $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandStartTextWithoutStore() . $this->tester->newLine(),
            $this->tester->repeat('-') . $this->tester->newLine(),
            $this->tester->newLine(),
            'foo' . $this->tester->newLine(),
            'bar' . $this->tester->newLine(),
            'baz' . $this->tester->newLine(),
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
