<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Install\Stage;

use Codeception\Test\Unit;
use Spryker\Zed\Install\Business\Stage\Section\Exception\SectionExistsException;
use Spryker\Zed\Install\Business\Stage\Section\Section;
use Spryker\Zed\Install\Business\Stage\Stage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Install
 * @group Stage
 * @group StageTest
 * Add your own group annotations below this line
 */
class StageTest extends Unit
{
    public const STAGE_NAME = 'stage';

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
