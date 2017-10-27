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
 * @group SetupConsoleCommandStoresTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandStoresTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteAllStores()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'stores',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
        $this->assertRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
    }

    /**
     * @return void
     */
    public function testExecuteSpecificStore()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'stores',
            SetupConsoleCommand::ARGUMENT_STORE => 'DE',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
    }
}
