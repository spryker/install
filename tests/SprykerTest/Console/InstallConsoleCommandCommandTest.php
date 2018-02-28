<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandCommandTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandCommandTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExcludedCommandByNameIsNotExecuted()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_EXCLUDE => ['section-a-command-a'],
        ];
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testRunsCommandWithSpecifiedTimeout()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'timeout',
            '--' . InstallConsoleCommand::OPTION_EXCLUDE => ['section-a-command-b'],
        ];
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was expected to run properly but was not.');
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenCommandRunsLongerThanConfiguredTimeout()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'timeout',
        ];

        $this->expectException(ProcessTimedOutException::class);
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);
    }
}
