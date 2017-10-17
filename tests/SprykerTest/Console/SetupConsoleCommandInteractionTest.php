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
            'stage' => 'interactive',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['yes', 'no']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Execute command line command: section-a-command-a/', $output);
        $this->assertNotRegexp('/Execute command line command: section-b-command-a/', $output);
    }
}
