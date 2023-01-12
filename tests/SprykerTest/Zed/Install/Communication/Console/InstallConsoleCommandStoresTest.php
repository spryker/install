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
 * @group InstallConsoleCommandStoresTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandStoresTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWithoutStoreArgumentAllStoresAreExecuted(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores',
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
    public function testWhenStoreArgumentIsSetOnlyTheRequestedStoreIsExecuted(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores',
            InstallConsole::ARGUMENT_STORE => 'DE',
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
    public function testOnlyCommandsWhichAreAwareOfRequestedStoreAreExecuted(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores',
            InstallConsole::ARGUMENT_STORE => 'US',
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
    public function testSelectOneStoreByPositionInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
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
    public function testSelectOneStoreByNameInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
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
    public function testSelectMultipleStoresByPositionInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
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
    public function testSelectMultipleStoresByNameInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
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
    public function testSelectAllStoresByPositionInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
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
    public function testSelectAllStoresByNameInInteractiveMode(): void
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores-interactive',
            '--' . InstallConsole::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['all', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a for DE store/', $output, 'Command "section-a-command-a" was expected to be executed for DE store but was not');
        $this->assertRegexp('/Command section-a-command-a for AT store/', $output, 'Command "section-a-command-a" was expected to be executed for AT store but was not');
        $this->assertRegexp('/Command section-a-command-a for US store/', $output, 'Command "section-a-command-a" was expected to be executed for US store but was not');
    }

    /**
     * @return void
     */
    public function testRunThrowsExceptionIfStoreArgumentIsInvalid(): void
    {
        //Arrange
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'stores',
            InstallConsole::ARGUMENT_STORE => 1,
        ];

        //Assert
        $this->expectException(InstallException::class);

        //Act
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testRunThrowsExceptionIfRecipeArgumentIsInvalid(): void
    {
        //Arrange
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 1,
        ];

        //Assert
        $this->expectException(InstallException::class);

        //Act
        $tester->execute($arguments);
    }
}
