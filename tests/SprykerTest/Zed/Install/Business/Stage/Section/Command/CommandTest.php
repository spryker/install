<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Install\Stage\Section\Command;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\Section\Command\Command;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Install
 * @group Stage
 * @group Section
 * @group Command
 * @group CommandTest
 * Add your own group annotations below this line
 */
class CommandTest extends Unit
{
    public const COMMAND_NAME = 'command';

    /**
     * @return void
     */
    public function testGetName()
    {
        $command = new Command(static::COMMAND_NAME);

        $this->assertSame(static::COMMAND_NAME, $command->getName());
    }

    /**
     * @return void
     */
    public function testSetExecuteString()
    {
        $command = new Command(static::COMMAND_NAME);
        $command->setExecutable('string to execute');

        $this->assertSame('string to execute', $command->getExecutable());
    }
}
