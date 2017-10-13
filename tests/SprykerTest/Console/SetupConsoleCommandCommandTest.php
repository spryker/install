<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Console;

use Codeception\Test\Unit;
use Spryker\Console\SetupConsoleCommand;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Console
 * @group SetupConsoleCommandCommandTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandCommandTest extends Unit
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
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            'stage' => 'development',
            '--' . SetupConsoleCommand::OPTION_EXCLUDE => ['section-a-command-a'],
        ];
        $tester->execute($arguments);

        $output = $tester->getDisplay();
        $this->assertNotRegexp('/Execute command line command: section-a-command-a/', $output);
    }
}
