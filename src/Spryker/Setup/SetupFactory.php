<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup;

use Spryker\Setup\Configuration\Builder\ConfigurationBuilder;
use Spryker\Setup\Configuration\Configuration;
use Spryker\Setup\Configuration\Loader\ConfigurationLoader;
use Spryker\Setup\Configuration\Validator\ConfigurationValidator;

class SetupFactory
{
    /**
     * @return \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
     */
    public function createConfigurationBuilder()
    {
        return new ConfigurationBuilder(
            $this->createConfigurationLoader(),
            $this->createConfiguration()
        );
    }

    /**
     * @return \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected function createConfigurationLoader()
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected function createConfigurationValidator()
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected function createConfiguration()
    {
        return new Configuration();
    }
}
