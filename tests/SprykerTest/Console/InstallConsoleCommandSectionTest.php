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
 * @group SprykerTest
 * @group Console
 * @group InstallConsoleCommandSectionTest
 * Add your own group annotations below this line
 */
class InstallConsoleCommandSectionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunOnlySpecifiedSection()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_SECTIONS => ['section-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section section-a/', $output);
        $this->assertNotRegexp('/Section section-(b|b|c)/', $output);
    }

    /**
     * @return void
     */
    public function testRunOnlySpecifiedSectionWhichIsExcludedByDefault()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_SECTIONS => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section section-c/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunExcludedSection()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_EXCLUDE => ['section-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Section section-a/', $output);
        $this->assertRegexp('/Section section-(b|b|c)/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedSection()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Section section-c/', $output, 'Section "section-c" was not expected to be executed but was');
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedSection()
    {
        $command = new InstallConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . InstallConsoleCommand::OPTION_RECIPE => 'development',
            '--' . InstallConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section section-c/', $output);
    }
}
