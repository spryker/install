<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration;

interface ConfigurationBuilderInterface
{
    /**
     * @param string $stageName
     *
     * @return \Spryker\Configuration\Configuration|\Spryker\Configuration\ConfigurationInterface
     */
    public function buildConfiguration($stageName);
}
