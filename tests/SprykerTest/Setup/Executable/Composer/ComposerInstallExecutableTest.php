<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Setup\Executable\Composer;

use Codeception\Test\Unit;
use Spryker\Setup\Executable\Composer\ComposerInstallExecutable;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Setup
 * @group Executable
 * @group Composer
 * @group ComposerInstallExecutableTest
 * Add your own group annotations below this line
 */
class ComposerInstallExecutableTest extends Unit
{
    /**
     * @return void
     */
    public function setUp()
    {
        $this->removeTestFile();
    }

    /**
     * @return void
     */
    public function testInstallIsExecutedWhenComposerNotFound()
    {
        $composerInstallExecutableMock = $this->createComposerInstallExecutableMock();
        $composerInstallExecutableMock->expects($this->once())->method('installComposer');
        $composerInstallExecutableMock->expects($this->never())->method('updateComposer');

        $composerInstallExecutableMock->execute($this->createStyleInterfaceMock());
    }

    /**
     * @return void
     */
    public function testUpdateIsExecutedWhenComposerFound()
    {
        $this->createTestFile();

        $composerInstallExecutableMock = $this->createComposerInstallExecutableMock();
        $composerInstallExecutableMock->expects($this->never())->method('installComposer');
        $composerInstallExecutableMock->expects($this->once())->method('updateComposer');

        $composerInstallExecutableMock->execute($this->createStyleInterfaceMock());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Setup\Executable\Composer\ComposerInstallExecutable
     */
    protected function createComposerInstallExecutableMock()
    {
        $mockBuilder = $this->getMockBuilder(ComposerInstallExecutable::class)->setMethods(['getPathToComposer', 'installComposer', 'updateComposer']);
        $mock = $mockBuilder->getMock();
        $mock->method('getPathToComposer')->willReturn($this->getPathToComposer());

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Console\Style\StyleInterface
     */
    protected function createStyleInterfaceMock()
    {
        $mock = $this->getMockForAbstractClass(StyleInterface::class);

        return $mock;
    }

    /**
     * @return string
     */
    protected function getPathToComposer()
    {
        return __DIR__ . '/../../../../_data/Fixtures/composer-test.phar';
    }

    /**
     * @return void
     */
    protected function removeTestFile()
    {
        if (file_exists($this->getPathToComposer())) {
            unlink($this->getPathToComposer());
        }
    }

    /**
     * @return void
     */
    protected function createTestFile()
    {
        if (!file_exists($this->getPathToComposer())) {
            $directory = dirname($this->getPathToComposer());
            if (!is_dir($directory)) {
                mkdir($directory);
            }
            touch($this->getPathToComposer());
        }
    }
}
