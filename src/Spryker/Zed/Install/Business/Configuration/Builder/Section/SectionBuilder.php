<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Builder\Section;

use Spryker\Zed\Install\Business\Stage\Section\Section;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;

class SectionBuilder implements SectionBuilderInterface
{
    /**
     * @var string
     */
    public const CONFIG_EXCLUDED = 'excluded';
    /**
     * @var string
     */
    public const CONFIG_PRE_COMMAND = 'pre';
    /**
     * @var string
     */
    public const CONFIG_POST_COMMAND = 'post';

    /**
     * @param string $name
     * @param array $definition
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\SectionInterface
     */
    public function buildSection(string $name, array $definition): SectionInterface
    {
        $section = new Section($name);
        $this->setExcluded($section, $definition);
        $this->setPreCommand($section, $definition);
        $this->setPostCommand($section, $definition);

        return $section;
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     * @param array $definition
     *
     * @return void
     */
    protected function setExcluded(SectionInterface $section, array $definition): void
    {
        if (isset($definition[static::CONFIG_EXCLUDED]) && $definition[static::CONFIG_EXCLUDED]) {
            $section->markAsExcluded();
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     * @param array $definition
     *
     * @return void
     */
    protected function setPreCommand(SectionInterface $section, array $definition): void
    {
        if (isset($definition[static::CONFIG_PRE_COMMAND])) {
            $section->setPreCommand($definition[static::CONFIG_PRE_COMMAND]);
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     * @param array $definition
     *
     * @return void
     */
    protected function setPostCommand(SectionInterface $section, array $definition): void
    {
        if (isset($definition[static::CONFIG_POST_COMMAND])) {
            $section->setPostCommand($definition[static::CONFIG_POST_COMMAND]);
        }
    }
}
