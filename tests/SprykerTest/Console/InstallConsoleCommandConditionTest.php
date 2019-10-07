<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;
use Spryker\Install\Stage\Section\Command\Condition\Exception\ConditionNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandConditionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandConditionTest extends Unit
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'condition',
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'condition',
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'condition',
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'condition-not-found',
        ];

        $this->expectException(ConditionNotFoundException::class);
        $tester->execute($arguments);
    }
}
