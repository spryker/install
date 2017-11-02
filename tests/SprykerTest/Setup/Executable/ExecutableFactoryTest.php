<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Setup\Executable;

use Codeception\Test\Unit;
use Spryker\Setup\Configuration\Configuration;
use Spryker\Setup\Executable\CommandLine\CommandLineExecutable;
use Spryker\Setup\Executable\Composer\ComposerInstallExecutable;
use Spryker\Setup\Executable\ExecutableFactory;
use Spryker\Setup\Stage\Section\Command\Command;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Setup
 * @group Executable
 * @group ExecutableFactoryTest
 * Add your own group annotations below this line
 */
class ExecutableFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateComposerExecutableByShortCut()
    {
        $command = new Command('composer');
        $command->setExecutable('composer');
        $executableFactory = new ExecutableFactory();

        $executable = $executableFactory->createExecutableFromCommand($command, $this->getConfiguration());

        $this->assertInstanceOf(ComposerInstallExecutable::class, $executable);
    }

    /**
     * @return void
     */
    public function testCreateCommandLineExecutable()
    {
        $command = new Command('ls -la');
        $executableFactory = new ExecutableFactory();

        $executable = $executableFactory->createExecutableFromCommand($command, $this->getConfiguration());

        $this->assertInstanceOf(CommandLineExecutable::class, $executable);
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
