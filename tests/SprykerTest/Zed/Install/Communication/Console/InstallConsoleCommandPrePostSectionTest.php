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
 * @group InstallConsoleCommandPrePostSectionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandPrePostSectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeSectionCommands()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-pre/', $output, 'Command "hidden-command-pre" was expected to be executed before section commands but was not');
    }

    /**
     * @return void
     */
    public function testPostCommandIsExecutedAfterSectionCommands()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before section commands but was not');
    }
}
