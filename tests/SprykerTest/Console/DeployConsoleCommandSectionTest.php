<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandSectionTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandSectionTest extends Unit
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . DeployConsoleCommand::OPTION_SECTIONS => ['section-a'],
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . DeployConsoleCommand::OPTION_SECTIONS => ['section-c'],
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . DeployConsoleCommand::OPTION_EXCLUDE => ['section-a'],
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . DeployConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section section-c/', $output);
    }
}
