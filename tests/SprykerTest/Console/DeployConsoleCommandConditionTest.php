<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;
use Spryker\Deploy\Stage\Section\Command\Condition\Exception\ConditionNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandConditionTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandConditionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteCommandOnlyIfPreviousCommandExitCodeMatches()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testNotExecuteCommandIfPreviousCommandExitCodeDoesNotMatch()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-b-command-b/', $output, 'Command "section-b-command-b" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testExecuteCommandIfConditionsCommandExitCodeDoesNotMatch()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-b-command-c/', $output, 'Command "section-b-command-c" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testThrowExceptionWhenConditionCanNotBeCreated()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'condition-not-found',
        ];

        $this->expectException(ConditionNotFoundException::class);
        $tester->execute($arguments);
    }
}
