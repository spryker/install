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
 * @group InstallConsoleCommandInteractionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandInteractionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunOnlySectionWhichConfirmedInteractively()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['yes', 'no']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
        $this->assertNotRegexp('/Command section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed but was');
    }
}
