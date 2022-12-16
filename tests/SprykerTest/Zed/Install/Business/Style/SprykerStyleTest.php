<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Business\Style;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\StageInterface;
use Spryker\Zed\Install\Business\Style\SprykerStyle;
use Spryker\Zed\Install\Business\Timer\TimerInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Business
 * @group Style
 * @group SprykerStyleTest
 * Add your own group annotations below this line
 */
class SprykerStyleTest extends Unit
{
    /**
     * @return void
     */
    public function testStartInstallShouldBeWritelnCorrectStrings(): void
    {
        // Arrange
        $outputMessages = [];
        $expectedOutputMessages = [
            '========================================================================================================================',
            'Install <fg=green></> environment                                                                                                                        ',
            '========================================================================================================================',
        ];
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMock();
        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMock();
        $timerMock = $this->getMockBuilder(TimerInterface::class)->getMock();
        $startMock = $this->getMockBuilder(StageInterface::class)->getMock();
        $outputFormatterMock = $this->getMockBuilder(OutputFormatterInterface::class)->getMock();
        $outputFormatterMock->method('isDecorated')->willReturn(true);
        $outputMock->method('getFormatter')->willReturn($outputFormatterMock)->getMock();
        $outputMock->expects($this->once())->method('writeln')->with($this->isType('array'))->will($this->returnCallback(function ($messages) use (&$outputMessages) {
            $outputMessages = $messages;
        }));

        $sprykerStyle = new SprykerStyle($inputMock, $outputMock, $timerMock);

        // Act
        $sprykerStyle->startInstall($startMock);

        // Assert
        $this->assertSame($expectedOutputMessages, $outputMessages);
    }
}
