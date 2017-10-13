<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Configuration;

use Spryker\Configuration\Command\Command;
use Spryker\Configuration\Filter\CommandFilter;
use Spryker\Configuration\Filter\InteractiveCommandFilter;
use Spryker\Configuration\Filter\InteractiveSectionFilter;
use Spryker\Configuration\Filter\SectionFilter;
use Spryker\Configuration\Section\Section;
use Spryker\Configuration\Section\SectionConfigurationInterface;
use Spryker\Configuration\Stage\Stage;
use Spryker\Configuration\Stage\StageConfigurationInterface;
use Spryker\Configuration\Validator\ConfigurationValidatorInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    const CONFIG_EXCLUDED = 'excluded';
    const CONFIG_ENV = 'env';
    const CONFIG_GROUPS = 'groups';

    /**
     * @var \Spryker\Configuration\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

    /**
     * @var \Spryker\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected $configurationValidator;

    /**
     * @var array
     */
    protected $sectionsToBeExecuted;

    /**
     * @var array
     */
    protected $groupsToBeExecuted;

    /**
     * @var array
     */
    protected $excludedSectionsAndGroups;

    /**
     * @var array
     */
    protected $includeExcluded;

    /**
     * @var bool
     */
    protected $isInteractive;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $style;

    /**
     * @var \Spryker\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Configuration\ConfigurationLoaderInterface $configurationLoader
     * @param \Spryker\Configuration\Validator\ConfigurationValidatorInterface $configurationValidator
     * @param array $sectionsToBeExecuted
     * @param array $groupsToBeExecuted
     * @param array $excludedStagesAndExcludedGroups
     * @param array $includeExcluded
     * @param bool $isInteractive
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     */
    public function __construct(
        ConfigurationLoaderInterface $configurationLoader,
        ConfigurationValidatorInterface $configurationValidator,
        array $sectionsToBeExecuted,
        array $groupsToBeExecuted,
        array $excludedStagesAndExcludedGroups,
        array $includeExcluded,
        $isInteractive,
        SymfonyStyle $style
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->configurationValidator = $configurationValidator;
        $this->sectionsToBeExecuted = $sectionsToBeExecuted;
        $this->groupsToBeExecuted = $groupsToBeExecuted;
        $this->excludedSectionsAndGroups = $excludedStagesAndExcludedGroups;
        $this->includeExcluded = $includeExcluded;
        $this->isInteractive = $isInteractive;
        $this->style = $style;

        $this->configuration = new Configuration();
    }

    /**
     * @param string $stageName
     *
     * @return \Spryker\Configuration\Configuration|\Spryker\Configuration\ConfigurationInterface
     */
    public function buildConfiguration($stageName)
    {
        $configuration = $this->loadConfiguration();

        $this->setEnv($configuration);
        $this->addStagesToConfiguration($stageName, $configuration['sections']);

        return $this->configuration;
    }

    /**
     * @return array
     */
    protected function loadConfiguration()
    {
        $configuration = $this->configurationLoader->loadConfiguration();
        $this->configurationValidator->validate($configuration);

        return $configuration;
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
     * @return \Spryker\Configuration\Filter\FilterInterface
     */
    protected function getSectionFilter()
    {
        return new SectionFilter(
            $this->includeExcluded,
            $this->sectionsToBeExecuted,
            $this->groupsToBeExecuted,
            $this->excludedSectionsAndGroups
        );
    }

    /**
     * @param string $sectionName
     * @param array $commands
     * @param \Spryker\Configuration\Stage\StageConfigurationInterface $stage
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
     * @return \Spryker\Configuration\Filter\FilterInterface
     */
    protected function getCommandFilter()
    {
        if ($this->isInteractive) {
            return new InteractiveCommandFilter($this->style);
        }

        return new CommandFilter(
            $this->includeExcluded,
            $this->groupsToBeExecuted,
            $this->excludedSectionsAndGroups
        );
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     * @param \Spryker\Configuration\Section\SectionConfigurationInterface $section
     *
     * @return void
     */
    protected function addCommandsToSection($commandName, $commandDefinition, SectionConfigurationInterface $section)
    {
        $command = new Command($commandName);
        $command->setExecutable($commandDefinition['command']);

        if (isset($commandDefinition[static::CONFIG_GROUPS])) {
            $command->setGroups($commandDefinition[static::CONFIG_GROUPS]);
        }

        if (isset($commandDefinition[static::CONFIG_ENV])) {
            $command->setEnv($commandDefinition[static::CONFIG_ENV]);
        }

        $section->addCommand($command);
    }
}
