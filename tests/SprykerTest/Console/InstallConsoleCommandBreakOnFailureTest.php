<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandBreakOnFailureTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandBreakOnFailureTest extends Unit
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'break-on-failure',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-b/', $output, 'Command "section-a-command-b" was not expected to be executed but was');
    }
}
