<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\Install\Communication\Console;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Configuration\Exception\ConfigurationException;
use Spryker\Zed\Install\Business\Configuration\Loader\Exception\ConfigurationFileNotFoundException;
use Spryker\Zed\Install\Communication\Console\InstallConsole;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Install
 * @group Communication
 * @group Console
 * @group InstallConsoleCommandTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Install\InstallCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testThrowsExceptionWhenConfigFileNotExistsSetupShowsConfigToBeExecuted()
    {
        $this->expectException(ConfigurationFileNotFoundException::class);

        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'catface',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenConfigFileDoesNotContainSections()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('No sections defined in your configuration.');

        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'no-sections',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenASectionHasNoCommands()
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('No commands defined in section "section-one".');

        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'no-command',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testLoadRecipeWithTypeSuffix()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development.yml',
        ];
        $tester->execute($arguments);
    }

    /**
     * @return void
     */
    public function testGlobalEnvFromConfigFileIsSet()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
        ];
        $tester->execute($arguments);

        $this->assertSame(getenv('env-key-a'), 'env-value-a');
    }

    /**
     * @return void
     */
    public function testDryRun()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_DRY_RUN => true,
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Dry-run: section-a-command-a/', $output);
        $this->assertNotRegexp('/Command section-a-command-a/', $output);
    }

    /**
     * @return void
     */
    public function testWithLoggerEnabled()
    {
        $command = new InstallConsole();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsole::OPTION_RECIPE => 'development',
            '--' . InstallConsole::OPTION_DRY_RUN => true,
            '--' . InstallConsole::OPTION_LOG => true,
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Dry-run: section-a-command-a/', $output);
        $this->assertNotRegexp('/Command section-a-command-a/', $output);
    }
}
