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
 * @group DeployConsoleCommandPrePostSectionTest
 * Add your own group annotations below this line
 */
class DeployConsoleCommandPrePostSectionTest extends Unit
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
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-pre/', $output, 'Command "hidden-command-pre" was expected to be executed before section commands but was not');
    }

    /**
     * @return void
     */
    public function testPostCommandIsExecutedAfterSectionCommands()
    {
        $command = new DeployConsoleCommand();
        $tester = $this->tester->getCommandTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . DeployConsoleCommand::OPTION_RECIPE => 'pre-post-section',
        ];

        $tester->execute($arguments);
        $output = $tester->getDisplay();

        $this->assertRegexp('/Command hidden-command-post/', $output, 'Command "hidden-command-post" was expected to be executed before section commands but was not');
    }
}
