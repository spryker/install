<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\DeployConsoleCommand;
use Spryker\Deploy\Exception\DeployException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group DeployConsoleCommandResumeTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandResumeTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInteractiveResume()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'interactive',
            '--' . DeployConsoleCommand::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'yes']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command section-a-command-a/', $output);
        $this->assertRegexp('/Command section-b-command-a/', $output);
    }

    /**
     * @return void
     */
    public function testInteractiveStopResume()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'interactive',
            '--' . DeployConsoleCommand::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'no']);

        $this->expectException(DeployException::class);
        $tester->execute($arguments);
    }
}
