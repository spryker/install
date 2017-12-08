<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandGroupTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandGroupTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunOnlySpecifiedGroup()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_GROUPS => ['group-a'],
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_EXCLUDE => ['group-a'],
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-d-command-b'],
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['group-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-d-command-b/', $output);
    }
}
