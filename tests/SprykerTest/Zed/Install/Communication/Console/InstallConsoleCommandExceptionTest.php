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
 * @group InstallConsoleCommandExceptionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandExceptionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testContinuesAfterFailedCommandWhenConfirmed()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'exceptions',
            '--' . InstallConsole::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testAbortsAfterFailedCommandWhenNotConfirmed()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'exceptions',
            '--' . InstallConsole::OPTION_ASK_BEFORE_CONTINUE => true,
        ];

        $tester->setInputs(['not']);

        $this->expectException(InstallException::class);

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was expected but not given');
        $this->assertNotRegexp('/Command successful-command/', $output, 'Command "successful-command" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testSilentlyContinueAfterFailedCommand()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'exceptions',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command failing-command failed! Continue with install?/', $output, 'Confirmation to continue was not expected but given');
        $this->assertRegexp('/Command successful-command/', $output, 'Command "successful-command" was expected to be executed but was not');
    }
}
