<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandBreakOnFailureTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandBreakOnFailureTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFailedCommandDoesNotBreakSetup()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'break-on-failure',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-b/', $output, 'Command "section-a-command-b" was not expected to be executed but was');
    }
}
