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
 * @group InstallConsoleCommandInteractionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandInteractionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunOnlySectionWhichConfirmedInteractively()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['yes', 'no']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
        $this->assertNotRegexp('/Command section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed but was');
    }
}
