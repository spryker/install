<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable\Collection;

use Spryker\Setup\Executable\ExecutableInterface;

interface ExecutableCollectionInterface
{
    /**
     * @param string $name
     * @param \Spryker\Setup\Executable\ExecutableInterface $executable
     *
     * @return $this
     */
    public function addExecutable($name, ExecutableInterface $executable);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasExecutable($name);

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Executable\ExecutableInterface
     */
    public function getExecutable($name);
}
