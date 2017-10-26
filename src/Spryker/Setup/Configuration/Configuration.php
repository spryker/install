<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Stage\Exception\StageExistsException;
use Spryker\Setup\Stage\StageInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Setup\Stage\StageInterface[]
     */
    protected $stages = [];

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @var array
     */
    protected $stores = [];

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @throws \Spryker\Setup\Stage\Exception\StageExistsException
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
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores)
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return array
     */
    public function getStores()
    {
        return $this->stores;
    }
}
