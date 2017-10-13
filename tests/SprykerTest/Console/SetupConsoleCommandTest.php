<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Configuration\Exception\ConfigurationException;
use Spryker\Configuration\Exception\ConfigurationFileNotFoundException;
use Spryker\Console\SetupConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionWhenConfigFileNotExistsSetupShowsConfigToBeExecuted()
    {
        $this->expectException(ConfigurationFileNotFoundException::class);

        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'catface',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenConfigFileDoesNotContainSections()
    {
        $this->expectException(ConfigurationException::class);

        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'no-sections',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testCommandInterfaceIsExecuted()
    {
        require_once __DIR__ . '/Fixtures/ExecutableCommand.php';

        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'executable',
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Executed CommandInterface/', $output);
    }

    /**
     * @return void
     */
    public function testGlobalEnvFromConfigFileIsSet()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
        ];
        $tester->execute($arguments);

        $this->assertSame(getenv('env-key-a'), 'env-value-a');
    }

    /**
     * @return void
     */
    public function testDryRun()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_DRY_RUN => true,
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Dry-run: section-a-command-a/', $output);
        $this->assertNotRegexp('/Execute command line command: section-a-command-a/', $output);
    }
}
