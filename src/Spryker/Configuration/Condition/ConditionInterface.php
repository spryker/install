<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Condition;

interface ConditionInterface
{
    /**
     * @param array $exitCodes
     *
     * @return bool
     */
    public function match(array $exitCodes);
}
