<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;
use Spryker\Install\Exception\InstallException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandExceptionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandExceptionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContinuesAfterFailedCommandWhenConfirmed()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'exceptions',
            '--' . InstallConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testAbortsAfterFailedCommandWhenNotConfirmed()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'exceptions',
            '--' . InstallConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['not']);

        $this->expectException(InstallException::class);

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertNotRegexp('/Command successful-command/', $output, 'Command "successful-command" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testSilentlyContinueAfterFailedCommand()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'exceptions',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was not expected but given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }
}
