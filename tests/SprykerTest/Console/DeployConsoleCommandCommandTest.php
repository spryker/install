<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandCommandTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandCommandTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExcludedCommandByNameIsNotExecuted()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            DeployConsoleCommand::ARGUMENT_ENVIRONMENT => 'development',
            '--' . DeployConsoleCommand::OPTION_EXCLUDE => ['section-a-command-a'],
        ];
        $tester->execute($arguments, ['verbosity' => OutputInterface::VERBOSITY_VERY_VERBOSE]);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Command section-a-command-a/', $output, 'Command "section-a-command-a" was not expected to be executed but was');
    }
}
