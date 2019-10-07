<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;
use Spryker\Install\Stage\Section\Command\Exception\CommandNotFoundException;
use Spryker\Install\Stage\Section\Exception\SectionNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandPrePostCommandTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandPrePostCommandTest extends Unit
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'pre-post-command',
            '--' . InstallConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-section'],
        ];

        $this->expectException(SectionNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfCommandByNameNotFound()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'pre-post-command',
            '--' . InstallConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-command'],
        ];

        $this->expectException(CommandNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeCommand()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'pre-post-command',
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before command but was not');
    }
}
