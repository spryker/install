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
use Spryker\Zed\Install\Communication\Console\InstallConsole;

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
     * @var int
     */
    protected const CODE_SUCCESS = 0;

    /**
     * @var string
     */
    protected const FAKE_COMMAND = 'echo';

    /**
     * @return void
     */
    public function testExecutesProcessIfFromShellCommandLineDoesNotExist(): void
    {
        //Arrange
        $command = new InstallConsole();
        $commandInterfaceMock = $this->createMock(CommandInterface::class);
        $commandInterfaceMock->expects($this->once())
            ->method('getExecutable')
            ->willReturn(static::FAKE_COMMAND);

        $configurationInterfaceMock = $this->createMock(ConfigurationInterface::class);

        $outputMock = $this->createMock(StyleInterface::class);
        $outputMock->expects($this->once())
            ->method('innerCommand');

        $commandLineMock = $this->getMockBuilder(CommandLineExecutable::class)
            ->setMethods(['processFromShellCommandlineMethodExists'])
            ->setConstructorArgs([$commandInterfaceMock, $configurationInterfaceMock])
            ->getMock();
        $commandLineMock->expects($this->once())
            ->method('processFromShellCommandlineMethodExists')
            ->willReturn(false);

        //Act
        $processResult = $commandLineMock->execute($outputMock);

        //Assert
        $this->assertEquals(static::CODE_SUCCESS, $processResult);
    }
}
