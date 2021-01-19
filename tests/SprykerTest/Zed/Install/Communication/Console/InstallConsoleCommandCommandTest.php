<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Communication\Console\InstallConsole;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandCommandTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandCommandTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExcludedCommandByNameIsNotExecuted(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_EXCLUDE => ['section-a-command-a'],
        ];
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testRunsCommandWithSpecifiedTimeout(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'timeout',
            '--' . InstallConsole::OPTION_EXCLUDE => ['section-a-command-b'],
        ];
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was expected to run properly but was not.');
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenCommandRunsLongerThanConfiguredTimeout(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'timeout',
        ];

        $this->expectException(ProcessTimedOutException::class);
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);
    }
}
