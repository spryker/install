<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Configuration\Builder\ConfigurationBuilder;
use Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface;
use Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilder;
use Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Setup\Configuration\Builder\Section\SectionBuilder;
use Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Setup\Configuration\Loader\ConfigurationLoader;
use Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Setup\Configuration\Validator\ConfigurationValidator;
use Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface;

class ConfigurationFactory
{
    /**
     * @return \Spryker\Setup\Configuration\Builder\ConfigurationBuilderInterface
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
     * @return \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected function createConfigurationLoader(): ConfigurationLoaderInterface
    {
        return new ConfigurationLoader(
            $this->createConfigurationValidator()
        );
    }

    /**
     * @return \Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected function createConfigurationValidator(): ConfigurationValidatorInterface
    {
        return new ConfigurationValidator();
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected function createConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected function createSectionBuilder(): SectionBuilderInterface
    {
        return new SectionBuilder();
    }

    /**
     * @return \Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected function createCommandBuilder(): CommandBuilderInterface
    {
        return new CommandBuilder(
            $this->createConditionFactory()
        );
    }

    /**
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionFactoryInterface
     */
    protected function createConditionFactory(): ConditionFactoryInterface
    {
        return new ConditionFactory();
    }
}
