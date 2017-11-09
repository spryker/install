<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Deploy\Stage;

use Codeception\Test\Unit;
use Spryker\Deploy\Stage\Section\Exception\SectionExistsException;
use Spryker\Deploy\Stage\Section\Section;
use Spryker\Deploy\Stage\Stage;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Setup
 * @group Stage
 * @group StageTest
 * Add your own group annotations below this line
 */
class StageTest extends Unit
{
    const STAGE_NAME = 'stage';

    /**
     * @return void
     */
    public function testGetName()
    {
        $stage = new Stage(static::STAGE_NAME);
        $this->assertSame(static::STAGE_NAME, $stage->getName());
    }

    /**
     * @return void
     */
    public function testAddSectionToStage()
    {
        $stage = new Stage(static::STAGE_NAME);
        $stage->addSection(new Section('section'));

        $this->assertCount(1, $stage->getSections());
    }

    /**
     * @return void
     */
    public function testAddSectionsToStage()
    {
        $stage = new Stage(static::STAGE_NAME);
        $stage
            ->addSection(new Section('sectionA'))
            ->addSection(new Section('sectionB'));

        $this->assertCount(2, $stage->getSections());
    }

    /**
     * @return void
     */
    public function testAddSectionsWithSameNameThrowsException()
    {
        $this->expectException(SectionExistsException::class);

        $stage = new Stage(static::STAGE_NAME);
        $stage
            ->addSection(new Section('sectionA'))
            ->addSection(new Section('sectionA'));
    }
}
