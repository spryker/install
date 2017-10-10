<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use PHPUnit_Framework_TestCase;
use Spryker\Console\SetupConsoleCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSetupShowsStageToBeExecuted()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
        ];
        $tester->execute($arguments);

        $this->assertRegexp('/Start setup for stage: development/', $tester->getDisplay());
    }

    /**
     * @return void
     */
    public function testRunOnlySpecifiedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
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
    public function testRunOnlySpecifiedGroup()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_GROUPS => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-b-command-a/', $output);
        $this->assertNotRegexp('/Execute command line command: (section-a-command-a|section-c-command-a|section-d-command-a|section-d-command-b)/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunExcludedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
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
    public function testDoNotRunExcludedGroup()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_EXCLUDE => ['group-a'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Execute command line command: section-b-command-a/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedSection()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
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
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Section: section-c/', $output);
    }

    /**
     * @return void
     */
    public function testDoNotRunDefaultExcludedGroup()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Execute command line command: section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByCommandName()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['section-d-command-b'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testIncludeDefaultExcludedGroupByGroupName()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_INCLUDE_EXCLUDED => ['group-c'],
        ];

        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-d-command-b/', $output);
    }

    /**
     * @return void
     */
    public function testDryRun()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->getCommandTester($command);

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

    /**
     * @param \Spryker\Configuration\Command\Command $command
     *
     * @return \Symfony\Component\Console\Tester\CommandTester
     */
    protected function getCommandTester(Command $command)
    {
        $application = new Application();
        $application->add($command);

        $command = $application->find('setup');

        return new CommandTester($command);
    }

}
