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
    public function testWithoutStoreArgumentAllStoresAreExecuted()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE but was not');
        $this->assertRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT but was not');
        $this->assertRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US but was not');
    }

    /**
     * @return void
     */
    public function testWhenStoreArgumentIsSetOnlyTheRequestedStoreIsExecuted()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            SetupConsoleCommand::ARGUMENT_STORE => 'DE',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectOneStoreByPositionInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs([1, 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectOneStoreByNameInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['DE', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectMultipleStoresByPositionInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['1,2', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectMultipleStoresByNameInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['DE,AT', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertNotRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectAllStoresByPositionInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs([0, 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
    }

    /**
     * @return void
     */
    public function testSelectAllStoresByNameInInteractiveMode()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'stores',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['all', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command: section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command: section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
    }
}
