<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section\Command;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getExecutable();

    /**
     * @return array
     */
    public function getEnv();

    /**
     * @return bool
     */
    public function isStoreAware();

    /**
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionInterface[]
     */
    public function getConditions();

    /**
     * @return bool
     */
    public function hasPreCommand();

    /**
     * @return string
     */
    public function getPreCommand();

    /**
     * @return bool
     */
    public function hasPostCommand();

    /**
     * @return string
     */
    public function getPostCommand();
}
