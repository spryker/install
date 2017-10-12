<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Configuration;

use Exception;
use Spryker\Configuration\Command\Command;
use Spryker\Configuration\Section\Section;
use Spryker\Configuration\Section\SectionConfigurationInterface;
use Spryker\Configuration\Stage\Stage;
use Spryker\Configuration\Stage\StageConfigurationInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    /**
     * @var \Spryker\Configuration\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

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
     * @var \Symfony\Component\Console\Style\StyleInterface
     */
    protected $style;

    /**
     * @var \Spryker\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @param \Spryker\Configuration\ConfigurationLoaderInterface $configurationLoader
     * @param array $sectionsToBeExecuted
     * @param array $groupsToBeExecuted
     * @param array $excludedStagesAndExcludedGroups
     * @param array $includeExcluded
     * @param bool $isInteractive
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     */
    public function __construct(
        ConfigurationLoaderInterface $configurationLoader,
        array $sectionsToBeExecuted,
        array $groupsToBeExecuted,
        array $excludedStagesAndExcludedGroups,
        array $includeExcluded,
        $isInteractive,
        SymfonyStyle $style
    ) {
        $this->configurationLoader = $configurationLoader;
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
        $configuration = $this->configurationLoader->getConfiguration();

        if (isset($configuration['env'])) {
            $this->configuration->setEnv($configuration['env']);
        }

        $this->addStagesToConfiguration($stageName, $configuration['sections']);

        return $this->configuration;
    }

    /**
     * @param string $stageName
     * @param array $sections
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function addStagesToConfiguration($stageName, array $sections)
    {
        $stage = new Stage($stageName);

        if (count($sections) === 0) {
            throw new Exception(sprintf('No sections defined in "%s" stage.', $stageName));
        }

        foreach ($sections as $sectionName => $commands) {
            if (isset($commands['excluded'])) {
                $isExcluded = $commands['excluded'];
                $shouldBeIncluded = ((count($this->includeExcluded) > 0 && in_array($sectionName, $this->includeExcluded)) || in_array($sectionName, $this->sectionsToBeExecuted));

                if ($isExcluded && !$shouldBeIncluded) {
                    continue;
                }
                unset($commands['excluded']);
            }

            $this->addSectionsToStage($sectionName, $commands, $stage);
        }

        $this->configuration->addStage($stage);
    }

    /**
     * @param string $sectionName
     * @param array $commands
     * @param \Spryker\Configuration\Stage\StageConfigurationInterface $stage
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function addSectionsToStage($sectionName, array $commands, StageConfigurationInterface $stage)
    {
        $section = new Section($sectionName);
        if (count($commands) === 0) {
            throw new Exception(sprintf('No commands defined in "%s" section.', $sectionName));
        }

        if ($this->isInteractive) {
            $shouldBeExecuted = $this->style->askQuestion(
                new ConfirmationQuestion(sprintf('Should section "%s" be executed?', $section->getName()))
            );

            if (!$shouldBeExecuted) {
                return;
            }
        }

        if (count($this->sectionsToBeExecuted) > 0 && !in_array($section->getName(), $this->sectionsToBeExecuted)) {
            return;
        }
        if (count($this->excludedStagesAndExcludedGroups) > 0 && in_array($section->getName(), $this->excludedStagesAndExcludedGroups)) {
            return;
        }

        foreach ($commands as $commandName => $commandDefinition) {
            if (isset($commandDefinition['excluded'])) {
                $shouldCommandBeIncluded = $this->shouldCommandBeIncluded($commandName, $commandDefinition);

                if (!$shouldCommandBeIncluded) {
                    continue;
                }
                unset($commandDefinition['excluded']);
            }

            $this->addCommandsToSection($commandName, $commandDefinition, $section);
        }
        $stage->addSection($section);
    }

    /**
     * @param string $commandName
     * @param array $commandDefinition
     *
     * @return bool
     */
    protected function shouldCommandBeIncluded($commandName, array $commandDefinition)
    {
        $isExcluded = $commandDefinition['excluded'];

        if ($isExcluded) {
            if (count($this->includeExcluded) > 0) {
                if (in_array($commandName, $this->includeExcluded)) {
                    return true;
                }

                if (isset($commandDefinition['groups']) && count(array_intersect($this->includeExcluded, $commandDefinition['groups']))) {
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

        if ($this->isInteractive) {
            $shouldBeExecuted = $this->style->askQuestion(
                new ConfirmationQuestion(sprintf('Should command "%s" be executed?', $command->getName()))
            );

            if (!$shouldBeExecuted) {
                return;
            }
        }

        if (isset($commandDefinition['groups'])) {
            $command->setGroups($commandDefinition['groups']);
        }

        if (isset($commandDefinition['env'])) {
            $command->setEnv($commandDefinition['env']);
        }

        if (count($this->excludedStagesAndExcludedGroups) > 0) {
            $intersect = array_intersect($this->excludedStagesAndExcludedGroups, $command->getGroups());
            if (count($intersect) > 0) {
                return;
            }
        }

        if (count($this->groupsToBeExecuted) > 0) {
            $intersect = array_intersect($this->groupsToBeExecuted, $command->getGroups());
            if (!count($intersect) > 0) {
                return;
            }
        }

        if (in_array($command->getName(), $this->excludedStagesAndExcludedGroups)) {
            return;
        }

        $section->addCommand($command);
    }
}
