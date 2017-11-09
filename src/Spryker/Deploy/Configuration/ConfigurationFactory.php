<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration;

use Spryker\Deploy\Configuration\Builder\ConfigurationBuilder;
use Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Deploy\Configuration\Builder\Section\Command\CommandBuilder;
use Spryker\Deploy\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Deploy\Configuration\Builder\Section\SectionBuilder;
use Spryker\Deploy\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Deploy\Configuration\Loader\ConfigurationLoader;
use Spryker\Deploy\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Deploy\Configuration\Validator\ConfigurationValidator;
use Spryker\Deploy\Configuration\Validator\ConfigurationValidatorInterface;
use Spryker\Deploy\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Deploy\Stage\Section\Command\Condition\ConditionFactoryInterface;

class ConfigurationFactory
{
    /**
     * @return \Spryker\Deploy\Configuration\Builder\ConfigurationBuilderInterface
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
     * @return \Spryker\Deploy\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected function createConfigurationLoader(): ConfigurationLoaderInterface
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Deploy\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected function createConfigurationValidator(): ConfigurationValidatorInterface
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    protected function createConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return \Spryker\Deploy\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected function createSectionBuilder(): SectionBuilderInterface
    {
        return new SectionBuilder();
    }

    /**
     * @return \Spryker\Deploy\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected function createCommandBuilder(): CommandBuilderInterface
    {
        return new CommandBuilder(
            $this->createConditionFactory()
        );
    }

    /**
     * @return \Spryker\Deploy\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    protected function createConditionFactory(): ConditionFactoryInterface
    {
        return new ConditionFactory();
    }
}
