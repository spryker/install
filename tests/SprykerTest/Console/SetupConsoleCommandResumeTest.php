<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;
use Spryker\Setup\Exception\SetupException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandResumeTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandResumeTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'interactive',
            '--' . SetupConsoleCommand::OPTION_BREAKPOINT => true,
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'interactive',
            '--' . SetupConsoleCommand::OPTION_BREAKPOINT => true,
        ];
        $tester->setInputs(['yes', 'no']);

        $this->expectException(SetupException::class);
        $tester->execute($arguments);
    }
}
