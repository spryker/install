<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandGroupTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandGroupTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . SetupConsoleCommand::OPTION_GROUPS => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-b-command-a/', $output);
        $this->assertNotRegexp('/Execute command line command: (section-a-command-a|section-c-command-a|section-d-command-a|section-d-command-b)/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunExcludedGroup()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . SetupConsoleCommand::OPTION_EXCLUDE => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command: section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedGroup()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command: section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByCommandName()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-d-command-b'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByGroupName()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['group-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-d-command-b/', $output);
    }
}
