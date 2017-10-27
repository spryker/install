<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;
use Spryker\Setup\Configuration\Exception\ConfigurationException;
use Spryker\Setup\Configuration\Loader\Exception\ConfigurationFileNotFoundException;

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
            SetupConsoleCommand::ARGUMENT_STAGE => 'catface',
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
            SetupConsoleCommand::ARGUMENT_STAGE => 'no-sections',
        ];
        $tester->execute($arguments);
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
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
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
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
            '--' . SetupConsoleCommand::OPTION_DRY_RUN => true,
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Dry-run: section-a-command-a/', $output);
        $this->assertNotRegexp('/Command: section-a-command-a/', $output);
    }
}
