<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Configuration\Filter\CommandFilter;
use Spryker\Setup\Configuration\Filter\InteractiveSectionFilter;
use Spryker\Setup\Configuration\Filter\SectionFilter;
use Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Setup\Configuration\Stage\Section\Command\CommandConfigurationInterface;
use Spryker\Setup\Configuration\Stage\Section\SectionConfigurationInterface;
use Spryker\Setup\Configuration\Stage\StageConfigurationInterface;
use Spryker\Setup\Stage\Section\Command\Command;
use Spryker\Setup\Stage\Section\Command\Condition\ConditionFactory;
use Spryker\Setup\Stage\Section\Section;
use Spryker\Setup\Stage\Stage;
use Symfony\Component\Console\Style\StyleInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    const CONFIG_EXCLUDED = 'excluded';
    const CONFIG_ENV = 'env';
    const CONFIG_STORES = 'stores';
    const CONFIG_GROUPS = 'groups';
    const CONFIG_CONDITIONS = 'conditions';

    /**
     * @var \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

    /**
     * @var \Spryker\Setup\CommandLine\CommandLineArgumentContainer
     */
    protected $commandLineArgumentContainer;

    /**
     * @var \Spryker\Setup\CommandLine\CommandLineOptionContainer
     */
    protected $commandLineOptionContainer;

    /**
     * @var array
     */
    protected $requestedSections;

    /**
     * @var array
     */
    protected $requestedGroups;

    /**
     * @var array
     */
    protected $exclude;

    /**
     * @var array
     */
    protected $includeExcluded;

    /**
     * @var bool
     */
    protected $isInteractive;

    /**
     * @var \Symfony\Component\Console\Style\StyleInterface
     */
    protected $style;

    /**
     * @var \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(
        ConfigurationLoaderInterface $configurationLoader,
        ConfigurationInterface $configuration
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->configuration = $configuration;
    }

    /**
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Symfony\Component\Console\Style\StyleInterface $style
     *
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $style
    ) {
        $this->commandLineArgumentContainer = $commandLineArgumentContainer;
        $this->commandLineOptionContainer = $commandLineOptionContainer;
        $this->style = $style;

        $configuration = $this->configurationLoader->loadConfiguration($commandLineArgumentContainer->getStage());

        $this->setEnv($configuration);
        $this->setStores($configuration);
        $this->addStagesToConfiguration($commandLineArgumentContainer->getStage(), $configuration['sections']);

        return $this->configuration;
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    protected function setEnv(array $configuration)
    {
        if (isset($configuration[static::CONFIG_ENV])) {
            $this->configuration->setEnv($configuration[static::CONFIG_ENV]);
        }
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    protected function setStores(array $configuration)
    {
        if (isset($configuration[static::CONFIG_STORES])) {
            $this->configuration->setStores($configuration[static::CONFIG_STORES]);
        }
    }

    /**
     * @param string $stageName
     * @param array $sections
     *
     * @return void
     */
    protected function addStagesToConfiguration($stageName, array $sections)
    {
        $stage = new Stage($stageName);

        foreach ($this->filterSections($sections) as $sectionName => $commands) {
            $this->addSectionToStage($sectionName, $commands, $stage);
        }

        $this->configuration->addStage($stage);
    }

    /**
     * @param array $sections
     *
     * @return array
     */
    protected function filterSections(array $sections)
    {
        return $this->getSectionFilter()->filter($sections);
    }

    /**
     * @return \Spryker\Setup\Configuration\Filter\FilterInterface
     */
    protected function getSectionFilter()
    {
        if ($this->commandLineOptionContainer->isInteractive()) {
            return new InteractiveSectionFilter($this->style);
        }

        return new SectionFilter(
            $this->commandLineOptionContainer->getIncludeExcluded(),
            $this->commandLineOptionContainer->getRequestedSections(),
            $this->commandLineOptionContainer->getRequestedGroups(),
            $this->commandLineOptionContainer->getExclude()
        );
    }

    /**
     * @param string $sectionName
     * @param array $commands
     * @param \Spryker\Setup\Configuration\Stage\StageConfigurationInterface $stage
     *
     * @return void
     */
    protected function addSectionToStage($sectionName, array $commands, StageConfigurationInterface $stage)
    {
        $section = new Section($sectionName);

        foreach ($this->filterCommands($commands) as $commandName => $commandDefinition) {
            $this->addCommandsToSection($commandName, $commandDefinition, $section);
        }

        $stage->addSection($section);
    }

    /**
     * @param array $commands
     *
     * @return array
     */
    protected function filterCommands(array $commands)
    {
        return $this->getCommandFilter()->filter($commands);
    }

    /**
     * @return \Spryker\Setup\Configuration\Filter\FilterInterface
     */
    protected function getCommandFilter()
    {
        return new CommandFilter(
            $this->commandLineOptionContainer->getIncludeExcluded(),
            $this->commandLineOptionContainer->getRequestedGroups(),
            $this->commandLineOptionContainer->getExclude()
        );
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     * @param \Spryker\Setup\Configuration\Stage\Section\SectionConfigurationInterface $section
     *
     * @return void
     */
    protected function addCommandsToSection($commandName, array $commandDefinition, SectionConfigurationInterface $section)
    {
        $command = new Command($commandName);
        $command->setExecutable($commandDefinition['command']);

        if (isset($commandDefinition[static::CONFIG_GROUPS])) {
            $command->setGroups($commandDefinition[static::CONFIG_GROUPS]);
        }

        if (isset($commandDefinition[static::CONFIG_ENV])) {
            $command->setEnv($commandDefinition[static::CONFIG_ENV]);
        }

        if (isset($commandDefinition[static::CONFIG_STORES])) {
            $command->setIsStoreAware($commandDefinition[static::CONFIG_STORES]);
        }

        if (isset($commandDefinition[static::CONFIG_CONDITIONS])) {
            $this->addCommandConditions($command, $commandDefinition[static::CONFIG_CONDITIONS]);
        }

        $section->addCommand($command);
    }

    /**
     * @param \Spryker\Setup\Configuration\Stage\Section\Command\CommandConfigurationInterface $command
     * @param array $conditions
     *
     * @return void
     */
    protected function addCommandConditions(CommandConfigurationInterface $command, array $conditions)
    {
        foreach ($conditions as $condition) {
            $condition = $this->getConditionFactory()->createCondition($condition);
            $command->addCondition($condition);
        }
    }

    /**
     * @return \Spryker\Setup\Stage\Section\Command\Condition\ConditionFactory
     */
    protected function getConditionFactory()
    {
        return new ConditionFactory();
    }
}
