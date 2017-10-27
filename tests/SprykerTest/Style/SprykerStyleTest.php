<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Style;

use Codeception\Test\Unit;
use Spryker\Style\SprykerStyle;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Style
 * @group SprykerStyleTest
 * Add your own group annotations below this line
 */
class SprykerStyleTest extends Unit
{
    /**
     * @return void
     */
    public function testTitle()
    {
        $sprykerStyle = $this->getSprykerStyle();
        $sprykerStyle->title('Title');
    }

    /**
     * @return void
     */
    public function testSection()
    {
        $sprykerStyle = $this->getSprykerStyle();
        $sprykerStyle->section('Section Name');
    }

    /**
     * @return void
     */
    public function testText()
    {
        $sprykerStyle = $this->getSprykerStyle();
        $sprykerStyle->text('Text output');
    }

    /**
     * @return \Spryker\Style\SprykerStyle
     */
    private function getSprykerStyle()
    {
        $input = new ArgvInput();
        $output = new ConsoleOutput();

        return new SprykerStyle($input, $output);
    }
}
