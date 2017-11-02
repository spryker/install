<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;
use Spryker\Setup\Exception\SetupException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandExceptionTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandExceptionTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'exceptions',
            '--' . SetupConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with setup?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertRegexp('/Command: successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testAbortsAfterFailedCommandWhenNotConfirmed()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'exceptions',
            '--' . SetupConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['not']);

        $this->expectException(SetupException::class);

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with setup?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertNotRegexp('/Command: successful-command/', $output, 'Command "successful-command" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testSilentlyContinueAfterFailedCommand()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'exceptions',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command failing-command failed! Continue with setup?/', $output, 'Confirmation to continue was not expected but given');
        $this->assertRegexp('/Command: successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }
}
