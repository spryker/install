<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration;

use Spryker\Configuration\Exception\StageExistsException;
use Spryker\Configuration\Stage\StageInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Configuration\Stage\StageInterface[]
     */
    protected $stages = [];

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param \Spryker\Configuration\Stage\StageInterface $stage
     *
     * @throws \Spryker\Configuration\Exception\StageExistsException
     *
     * @return $this
     */
    public function addStage(StageInterface $stage)
    {
        if (isset($this->stages[$stage->getName()])) {
            throw new StageExistsException(sprintf('Stage with name "%s" already exists', $stage->getName()));
        }

        $this->stages[$stage->getName()] = $stage;

        return $this;
    }

    /**
     * @return array
     */
    public function getStages()
    {
        return $this->stages;
    }
}
