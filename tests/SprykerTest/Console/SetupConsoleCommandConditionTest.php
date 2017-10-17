<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Configuration\Exception\ConfigurationException;
use Spryker\Console\SetupConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandConditionTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandConditionTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testNotExecuteCommandIfPreviousCommandExitCodeDoesNotMatch()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Execute command line command: section-b-command-b/', $output, 'Command "section-b-command-b" was not expected to be executed');
    }

    /**
     * @return void
     */
    public function testExecuteCommandIfPreviousCommandExitCodeDoesNotMatch()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'condition',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-b-command-c/', $output, 'Command "section-b-command-c" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testThrowExceptionWhenConditionCanNotBeCreated()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'condition-not-found',
        ];

        $this->expectException(ConfigurationException::class);
        $tester->execute($arguments);
    }
}
