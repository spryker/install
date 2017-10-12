<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Configuration;

use Spryker\Configuration\Command\Command;
use Spryker\Configuration\Command\CommandInterface;
use Spryker\Configuration\Section\Section;
use Spryker\Configuration\Section\SectionConfigurationInterface;
use Spryker\Configuration\Stage\Stage;
use Spryker\Configuration\Stage\StageConfigurationInterface;
use Spryker\Configuration\Validator\ConfigurationValidatorInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
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
    protected $excludedStagesAndExcludedGroups;

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
        $this->excludedStagesAndExcludedGroups = $excludedStagesAndExcludedGroups;
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
        if (isset($configuration[self::CONFIG_ENV])) {
            $this->configuration->setEnv($configuration[self::CONFIG_ENV]);
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
        $filteredSections = [];

        foreach ($sections as $sectionName => $sectionDefinition) {
            if (!$this->shouldSectionBeAdded($sectionName)) {
                continue;
            }
            if (isset($sectionDefinition[static::CONFIG_EXCLUDED])) {
                $isExcluded = $sectionDefinition[static::CONFIG_EXCLUDED];
                $shouldBeIncluded = ((count($this->includeExcluded) > 0 && in_array($sectionName, $this->includeExcluded)) || in_array($sectionName, $this->sectionsToBeExecuted));

                if ($isExcluded && !$shouldBeIncluded) {
                    continue;
                }
                unset($sectionDefinition[static::CONFIG_EXCLUDED]);
            }
            $filteredSections[$sectionName] = $sectionDefinition;
        }

        return $filteredSections;
    }

    /**
     * @param string $sectionName
     *
     * @return bool
     */
    protected function shouldSectionBeAdded($sectionName)
    {
        if ($this->isInteractive && $this->ask(sprintf('Should section "%s" be executed?', $sectionName)) === false) {
            return false;
        }

        if (count($this->sectionsToBeExecuted) > 0 && !in_array($sectionName, $this->sectionsToBeExecuted)) {
            return false;
        }

        if (count($this->excludedStagesAndExcludedGroups) > 0 && in_array($sectionName, $this->excludedStagesAndExcludedGroups)) {
            return false;
        }

        return true;
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
        $filteredCommands = [];
        foreach ($commands as $commandName => $commandDefinition) {
            if (isset($commandDefinition[self::CONFIG_EXCLUDED])) {
                $shouldCommandBeIncluded = $this->shouldCommandBeIncluded($commandName, $commandDefinition);

                if (!$shouldCommandBeIncluded) {
                    continue;
                }
                unset($commandDefinition[self::CONFIG_EXCLUDED]);
            }
            $filteredCommands[$commandName] = $commandDefinition;
        }

        return $filteredCommands;
    }

    /**
     * @param string $question
     *
     * @return bool
     */
    protected function ask($question)
    {
        return $this->style->askQuestion(
            new ConfirmationQuestion($question)
        );
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function shouldCommandBeIncluded($commandName, array $commandDefinition)
    {
        $isExcluded = $commandDefinition[self::CONFIG_EXCLUDED];

        if ($isExcluded) {
            if (count($this->includeExcluded) > 0) {
                if (in_array($commandName, $this->includeExcluded)) {
                    return true;
                }

                if (isset($commandDefinition[self::CONFIG_GROUPS]) && count(array_intersect($this->includeExcluded, $commandDefinition[self::CONFIG_GROUPS]))) {
                    return true;
                }
            }

            return false;
        }

        return true;
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

        if (!$this->shouldCommandBeAdded($command)) {
            return;
        }

        $section->addCommand($command);
    }

    /**
     * @param \Spryker\Configuration\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function shouldCommandBeAdded(CommandInterface $command)
    {
        if ($this->isInteractive && $this->ask(sprintf('Should section "%s" be executed?', $command->getName())) === false) {
            return false;
        }

        if (count($this->excludedStagesAndExcludedGroups) > 0 && (count(array_intersect($this->excludedStagesAndExcludedGroups, $command->getGroups())) > 0)) {
            return false;
        }

        if (count($this->groupsToBeExecuted) > 0 && !(count(array_intersect($this->groupsToBeExecuted, $command->getGroups())) > 0)) {
            return false;
        }

        if (in_array($command->getName(), $this->excludedStagesAndExcludedGroups)) {
            return false;
        }

        return true;
    }
}
