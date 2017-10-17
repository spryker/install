<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Condition;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @param string $command
     * @param int $exitCode
     */
    public function __construct($command, $exitCode)
    {
        $this->command = $command;
        $this->exitCode = $exitCode;
    }
}
