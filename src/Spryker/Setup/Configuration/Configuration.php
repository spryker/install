<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Stage\StageInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Setup\Stage\StageInterface
     */
    protected $stage;

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
     * @return $this
     */
    public function setStage(StageInterface $stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function getStage()
    {
        return $this->stage;
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

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function findCommand($name)
    {
        list($section, $command) = explode('/', $name);

        return $this->stage->getSection($section)->getCommand($command);
    }
}
