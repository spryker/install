<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Configuration;

use Spryker\Install\Configuration\Builder\ConfigurationBuilder;
use Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Install\Configuration\Builder\Section\Command\CommandBuilder;
use Spryker\Install\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Install\Configuration\Builder\Section\SectionBuilder;
use Spryker\Install\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Install\Configuration\Loader\ConfigurationLoader;
use Spryker\Install\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Install\Configuration\Validator\ConfigurationValidator;
use Spryker\Install\Configuration\Validator\ConfigurationValidatorInterface;
use Spryker\Install\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Install\Stage\Section\Command\Condition\ConditionFactoryInterface;

class ConfigurationFactory
{
    /**
     * @return \Spryker\Install\Configuration\Builder\ConfigurationBuilderInterface
     */
    public function createConfigurationBuilder(): ConfigurationBuilderInterface
    {
        return new ConfigurationBuilder(
            $this->createConfigurationLoader(),
            $this->createConfiguration(),
            $this->createSectionBuilder(),
            $this->createCommandBuilder()
        );
    }

    /**
     * @return \Spryker\Install\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected function createConfigurationLoader(): ConfigurationLoaderInterface
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Install\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected function createConfigurationValidator(): ConfigurationValidatorInterface
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    protected function createConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return \Spryker\Install\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected function createSectionBuilder(): SectionBuilderInterface
    {
        return new SectionBuilder();
    }

    /**
     * @return \Spryker\Install\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected function createCommandBuilder(): CommandBuilderInterface
    {
        return new CommandBuilder(
            $this->createConditionFactory()
        );
    }

    /**
     * @return \Spryker\Install\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    protected function createConditionFactory(): ConditionFactoryInterface
    {
        return new ConditionFactory();
    }
}
