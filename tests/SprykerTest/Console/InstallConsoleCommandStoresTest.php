<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\InstallConsoleCommand;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandStoresTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandStoresTest extends Unit
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
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');

        $this->assertRegexp('/Command section-b-command-a for DE store/', $output, 'Command "section-b-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-b-command-a for AT store/', $output, 'Command "section-b-command-a" was expected to be executed for AT store but was not');
    }

    /**
     * @return void
     */
    public function testWhenStoreArgumentIsSetOnlyTheRequestedStoreIsExecuted()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores',
            InstallConsoleCommand::ARGUMENT_STORE => 'DE',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');

        $this->assertRegexp('/Command section-b-command-a for DE store/', $output, 'Command "section-b-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command section-b-command-a for US store/', $output, 'Command "section-b-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command section-b-command-a for US store/', $output, 'Command "section-b-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testOnlyCommandsWhichAreAwareOfRequestedStoreAreExecuted()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores',
            InstallConsoleCommand::ARGUMENT_STORE => 'US',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was not expected to be executed for DE store but was');
        $this->assertNotRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');

        $this->assertNotRegexp('/Command section-b-command-a for US store/', $output, 'Command "section-b-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectOneStoreByPositionInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs([1, 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectOneStoreByNameInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['DE', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was not expected to be executed for AT store but was');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectMultipleStoresByPositionInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['1,2', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectMultipleStoresByNameInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['DE,AT', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertNotRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was not expected to be executed for US store but was');
    }

    /**
     * @return void
     */
    public function testSelectAllStoresByPositionInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs([0, 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
    }

    /**
     * @return void
     */
    public function testSelectAllStoresByNameInInteractiveMode()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['all', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
    }
}
