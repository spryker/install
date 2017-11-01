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
 * @group SetupConsoleCommandPrePostSectionTest
 * Add your own group annotations below this line
 */
class SetupConsoleCommandPrePostSectionTest extends Unit
{
    /**
     * @var \SprykerTest\ConsoleTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPreCommandIsExecutedBeforeSectionCommands()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command: hidden-command-pre/', $output, 'Command "hidden-command-pre" was expected to be executed before section commands but was not');
    }

    /**
     * @return void
     */
    public function testPostCommandIsExecutedAfterSectionCommands()
    {
        $command = new SetupConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            SetupConsoleCommand::ARGUMENT_ENVIRONMENT => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command: hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before section commands but was not');
    }
}
