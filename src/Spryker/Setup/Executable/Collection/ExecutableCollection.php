<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Executable\Collection;

use Spryker\Setup\Executable\ExecutableInterface;

class ExecutableCollection implements ExecutableCollectionInterface
{
    /**
     * @var array
     */
    protected $executables = [];

    /**
     * @param string $name
     * @param \Spryker\Setup\Executable\ExecutableInterface $executable
     *
     * @return $this
     */
    public function addExecutable($name, ExecutableInterface $executable)
    {
        $this->executables[$name] = $executable;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasExecutable($name)
    {
        return isset($this->executables[$name]);
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Executable\ExecutableInterface
     */
    public function getExecutable($name)
    {
        return $this->executables[$name];
    }
}
