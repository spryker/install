<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\Section\Command\Exception\CommandNotFoundException;
use Spryker\Zed\Install\Business\Stage\Section\Exception\SectionNotFoundException;
use Spryker\Zed\Install\Communication\Console\InstallConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandPrePostCommandTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandPrePostCommandTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionIfSectionByNameNotFound(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-command',
            '--' . InstallConsole::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-section'],
        ];

        $this->expectException(SectionNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfCommandByNameNotFound(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-command',
            '--' . InstallConsole::OPTION_INCLUDE_EXCLUDED => ['command-with-undefined-pre-command'],
        ];

        $this->expectException(CommandNotFoundException::class);

        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeCommand(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-pre/', $output, 'Command "hidden-command-pre" was expected to be executed before command but was not');
    }

    /**
     * @return void
     */
    public function testPostCommandIsExecutedBeforeCommand(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'pre-post-command',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before command but was not');
    }
}
