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
 * @group SetupConsoleCommandInteractionTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandInteractionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testInteractive()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_STAGE => 'interactive',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['yes', 'no']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a/', $output);
        $this->assertNotRegexp('/Command: section-b-command-a/', $output);
    }
}
