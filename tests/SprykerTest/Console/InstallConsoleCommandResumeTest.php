<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;
use Spryker\Install\Exception\InstallException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandResumeTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandResumeTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInteractiveResume()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'interactive',
            '--' . InstallConsoleCommand::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output);
        $this->assertRegexp('/Command section-b-command-a/', $output);
    }

    /**
     * @return void
     */
    public function testInteractiveStopResume()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'interactive',
            '--' . InstallConsoleCommand::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'no']);

        $this->expectException(InstallException::class);
        $tester->execute($arguments);
    }
}
