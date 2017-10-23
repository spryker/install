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
     * @return mixed
     */
    public function addStage(StageInterface $stage);

    /**
     * @return \Spryker\Setup\Stage\StageInterface[]
     */
    public function getStages();

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
}
