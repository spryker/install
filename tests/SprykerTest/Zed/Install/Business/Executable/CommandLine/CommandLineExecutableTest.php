<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Business\Executable\CommandLine;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Executable\CommandLine\CommandLineExecutable;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Style\StyleInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Business
 * @group Executable
 * @group CommandLine
 * @group CommandLineExecutableTest
 * Add your own group annotations below this line
 */
class CommandLineExecutableTest extends Unit
{
    /**
     * @return void
     */
    public function testExecutesProcessFromShellCommandLine(): void
    {
        //Arrange
        $commandInterfaceMock = $this->createMock(CommandInterface::class);
        $commandInterfaceMock->expects($this->once())
            ->method('getExecutable');

        $configurationInterfaceMock = $this->createMock(ConfigurationInterface::class);

        $outputMock = $this->createMock(StyleInterface::class);
        $outputMock->expects($this->once())
            ->method('innerCommand');

        $commandLineMock = $this->getMockBuilder(CommandLineExecutable::class)
            ->setMethods(['processFromShellCommandline'])
            ->setConstructorArgs([$commandInterfaceMock, $configurationInterfaceMock])
            ->getMock();
        $commandLineMock->expects($this->once())
            ->method('processFromShellCommandline')
            ->willReturn(null);

        //Act
        $processResult = $commandLineMock->execute($outputMock);

        //Assert
        $this->assertTrue(true, $processResult);
    }
}
