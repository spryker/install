<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\Section\Command\Condition\Exception\ConditionNotFoundException;
use Spryker\Zed\Install\Communication\Console\InstallConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandConditionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandConditionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteCommandOnlyIfPreviousCommandExitCodeMatches(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testNotExecuteCommandIfPreviousCommandExitCodeDoesNotMatch(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-b-command-b/', $output, 'Command "section-b-command-b" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testExecuteCommandIfConditionsCommandExitCodeDoesNotMatch(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-b-command-c/', $output, 'Command "section-b-command-c" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testThrowExceptionWhenConditionCanNotBeCreated(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'condition-not-found',
        ];

        $this->expectException(ConditionNotFoundException::class);
        $tester->execute($arguments);
    }
}
