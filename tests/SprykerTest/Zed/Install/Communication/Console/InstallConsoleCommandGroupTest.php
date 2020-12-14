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
 * @group InstallConsoleCommandGroupTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandGroupTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunOnlySpecifiedGroup()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_GROUPS => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-b-command-a/', $output);
        $this->assertNotRegexp('/Command (section-a-command-a|section-c-command-a|section-d-command-a|section-d-command-b)/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunExcludedGroup()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_EXCLUDE => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedGroup()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByCommandName()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_INCLUDE_EXCLUDED => ['section-d-command-b'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByGroupName()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_INCLUDE_EXCLUDED => ['group-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-d-command-b/', $output);
    }
}
