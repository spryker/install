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
 * @group SetupConsoleCommandSectionTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandSectionTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
            '--' . SetupConsoleCommand::OPTION_SECTIONS => ['section-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section: section-a/', $output);
        $this->assertNotRegexp('/Section: section-(b|b|c)/', $output);
    }

    /**
     * @return void
     */
    public function testRunOnlySpecifiedSectionWhichIsExcludedByDefault()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
            '--' . SetupConsoleCommand::OPTION_SECTIONS => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section: section-c/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunExcludedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
            '--' . SetupConsoleCommand::OPTION_EXCLUDE => ['section-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Section: section-a/', $output);
        $this->assertRegexp('/Section: section-(b|b|c)/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Section: section-c/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section: section-c/', $output);
    }
}
