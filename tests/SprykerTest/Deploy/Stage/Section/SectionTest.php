<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Deploy\Stage\Section;

use Codeception\Test\Unit;
use Spryker\Deploy\Stage\Section\Command\Command;
use Spryker\Deploy\Stage\Section\Command\Exception\CommandExistsException;
use Spryker\Deploy\Stage\Section\Section;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Setup
 * @group Stage
 * @group Section
 * @group SectionTest
 * Add your own group annotations below this line
 */
class SectionTest extends Unit
{
    const SECTION_NAME = 'section';

    /**
     * @return void
     */
    public function testGetName()
    {
        $section = new Section(static::SECTION_NAME);
        $this->assertSame(static::SECTION_NAME, $section->getName());
    }

    /**
     * @return void
     */
    public function testAddCommandToSection()
    {
        $section = new Section(static::SECTION_NAME);
        $section->addCommand(new Command('command'));

        $this->assertCount(1, $section->getCommands());
    }

    /**
     * @return void
     */
    public function testAddCommandsToSection()
    {
        $section = new Section(static::SECTION_NAME);
        $section
            ->addCommand(new Command('commandA'))
            ->addCommand(new Command('commandB'));

        $this->assertCount(2, $section->getCommands());
    }

    /**
     * @return void
     */
    public function testAddCommandsWithSameNameThrowsException()
    {
        $this->expectException(CommandExistsException::class);

        $section = new Section(static::SECTION_NAME);
        $section
            ->addCommand(new Command('commandA'))
            ->addCommand(new Command('commandA'));
    }
}
