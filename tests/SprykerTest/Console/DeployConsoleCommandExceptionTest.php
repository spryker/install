<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;
use Spryker\Deploy\Exception\DeployException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandExceptionTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandExceptionTest extends Unit
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'exceptions',
            '--' . DeployConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with deploy?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testAbortsAfterFailedCommandWhenNotConfirmed()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'exceptions',
            '--' . DeployConsoleCommand::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['not']);

        $this->expectException(DeployException::class);

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with deploy?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertNotRegexp('/Command successful-command/', $output, 'Command "successful-command" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testSilentlyContinueAfterFailedCommand()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'exceptions',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command failing-command failed! Continue with deploy?/', $output, 'Confirmation to continue was not expected but given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }
}
