<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Exception\InstallException;
use Spryker\Zed\Install\Communication\Console\InstallConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandResumeTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandResumeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInteractiveResume()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'interactive',
            '--' . InstallConsole::OPTION_BREAKPOINT => true,
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
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'interactive',
            '--' . InstallConsole::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'no']);

        $this->expectException(InstallException::class);
        $tester->execute($arguments);
    }
}
