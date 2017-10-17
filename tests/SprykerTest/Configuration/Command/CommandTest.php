<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Configuration\Command;

use Codeception\Test\Unit;
use Spryker\Configuration\Command\Command;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Configuration
 * @group Command
 * @group CommandTest
 * Add your own group annotations below this line
 */
class CommandTest extends Unit
{
    const COMMAND_NAME = 'command';

    /**
     * @return void
     */
    public function testGetName()
    {
        $command = new Command(self::COMMAND_NAME);

        $this->assertSame(self::COMMAND_NAME, $command->getName());
    }

    /**
     * @return void
     */
    public function testSetExecuteString()
    {
        $command = new Command(self::COMMAND_NAME);
        $command->setExecutable('string to execute');

        $this->assertSame('string to execute', $command->getExecutable());
    }
}
