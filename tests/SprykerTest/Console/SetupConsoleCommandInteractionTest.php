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
    public function testRunOnlySectionWhichConfirmedInteractively()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'interactive',
            '--' . SetupConsoleCommand::OPTION_INTERACTIVE => true,
        ];
        $tester->setInputs(['yes', 'no']);
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertRegexp('/Command: section-a-command-a/', $output, 'Command "section-a-command-a" was expected to be executed but was not');
        $this->assertNotRegexp('/Command: section-b-command-a/', $output, 'Command "section-b-command-a" was not expected to be executed but was');
    }
}
