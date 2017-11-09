<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;
use Spryker\Deploy\Stage\Section\Command\Exception\CommandNotFoundException;
use Spryker\Deploy\Stage\Section\Exception\SectionNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandPrePostCommandTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandPrePostCommandTest extends Unit
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
            '--' . DeployConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-section'],
        ];

        $this->expectException(SectionNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfCommandByNameNotFound()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
            '--' . DeployConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-command'],
        ];

        $this->expectException(CommandNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeCommand()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before command but was not');
    }
}
