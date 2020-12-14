<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Communication\Console\InstallConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandBreakOnFailureTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandBreakOnFailureTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFailedCommandDoesNotBreakSetup()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'break-on-failure',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-b/', $output, 'Command "section-a-command-b" was not expected to be executed but was');
    }
}
