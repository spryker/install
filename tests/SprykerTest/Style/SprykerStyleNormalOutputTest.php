<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Style;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Style
 * @group SprykerStyleNormalOutputTest
 * Add your own group annotations below this line
 */
class SprykerStyleNormalOutputTest extends Unit
{
    /**
     * @var \SprykerTest\StyleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testStartSetupNormalOutput()
    {
        $stage = $this->tester->getStage();
        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->startSetup($stage);

        $block = [
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getStageStartText()),
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testStartSectionNormalOutput()
    {
        $section = $this->tester->getSection();
        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->startSection($section);

        $block = [
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getSectionStartText()),
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testEndSectionNormalOutput()
    {
        $section = $this->tester->getSection();
        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->endSection($section);

        $this->assertSame($this->tester->newLine(), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testStartCommandNormalOutputWithStore()
    {
        $command = $this->tester->getCommand();

        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->startCommand($command, $this->tester->getStore());

        $block = [
            $this->tester->getCommandStartTextWithStore() . $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testStartCommandNormalOutputWithoutStore()
    {
        $command = $this->tester->getCommand();

        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->startCommand($command);

        $block = [
            $this->tester->getCommandStartTextWithoutStore() . $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testNoteNotPrintedInNormalOutput()
    {
        $sprykerStyle = $this->tester->getSprykerStyle();
        $sprykerStyle->note('My note');

        $this->assertSame('', $this->tester->getOutput());
    }

    /**
     * @return void
     */
    public function testFullOutput()
    {
        $this->tester->fakeFullSetupRun();

        $block = [
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getStageStartText()),
            $this->tester->repeat('=') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->fullLineLength($this->tester->getSectionStartText()),
            $this->tester->repeat(' ') . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getCommandEndOverwrittenText() . $this->tester->newLine(),
            $this->tester->getCommandEndOverwrittenText() . $this->tester->newLine(),
            $this->tester->newLine(),
            $this->tester->getStageEndText() . $this->tester->newLine(),
        ];

        $this->assertSame(implode($block), $this->tester->getOutput());
    }
}
