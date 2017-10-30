<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Stage\StageInterface;

interface ConfigurationInterface
{
    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage);

    /**
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function getStage();

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @return array
     */
    public function getEnv();

    /**
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores);

    /**
     * @return array
     */
    public function getStores();

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function findCommand($name);
}
