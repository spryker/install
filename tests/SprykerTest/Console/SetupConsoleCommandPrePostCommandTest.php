<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;
use Spryker\Setup\Stage\Section\Command\Exception\CommandNotFoundException;
use Spryker\Setup\Stage\Section\Exception\SectionNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandPrePostCommandTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandPrePostCommandTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionIfSectionByNameNotFound()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-section'],
        ];

        $this->expectException(SectionNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfCommandByNameNotFound()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-command'],
        ];

        $this->expectException(CommandNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeCommand()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-pre/', $output, 'Command "hidden-command-pre" was expected to be executed before command but was not');
    }

    /**
     * @return void
     */
    public function testPostCommandIsExecutedBeforeCommand()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before command but was not');
    }
}
