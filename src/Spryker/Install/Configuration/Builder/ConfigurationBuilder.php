<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Builder;

use Spryker\Install\CommandLine\CommandLineArgumentContainer;
use Spryker\Install\CommandLine\CommandLineOptionContainer;
use Spryker\Install\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Install\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Configuration\Filter\CommandExcludeFilter;
use Spryker\Install\Configuration\Filter\FilterComposite;
use Spryker\Install\Configuration\Filter\FilterInterface;
use Spryker\Install\Configuration\Filter\InteractiveSectionExcludeFilter;
use Spryker\Install\Configuration\Filter\SectionExcludeFilter;
use Spryker\Install\Configuration\Filter\UnsetFilter;
use Spryker\Install\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Install\Stage\Section\SectionInterface;
use Spryker\Install\Stage\Stage;
use Spryker\Style\StyleInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    const CONFIG_EXCLUDED = 'excluded';
    const CONFIG_ENV = 'env';
    const CONFIG_STORES = 'stores';
    const CONFIG_COMMAND_TIMEOUT = 'command-timeout';
    const CONFIG_GROUPS = 'groups';
    const CONFIG_CONDITIONS = 'conditions';
    const CONFIG_PRE_COMMAND = 'pre';
    const CONFIG_POST_COMMAND = 'post';
    const ALL_STORES = 'all';

    /**
     * @var \Spryker\Install\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

    /**
     * @var \Spryker\Install\CommandLine\CommandLineArgumentContainer
     */
    protected $commandLineArgumentContainer;

    /**
     * @var \Spryker\Install\CommandLine\CommandLineOptionContainer
     */
    protected $commandLineOptionContainer;

    /**
     * @var \Spryker\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Install\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var \Spryker\Install\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected $sectionBuilder;

    /**
     * @var \Spryker\Install\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected $commandBuilder;

    /**
     * @param \Spryker\Install\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     * @param \Spryker\Install\Configuration\Builder\Section\SectionBuilderInterface $sectionBuilder
     * @param \Spryker\Install\Configuration\Builder\Section\Command\CommandBuilderInterface $commandBuilder
     */
    public function __construct(
        ConfigurationLoaderInterface $configurationLoader,
        ConfigurationInterface $configuration,
        SectionBuilderInterface $sectionBuilder,
        CommandBuilderInterface $commandBuilder
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->configuration = $configuration;
        $this->sectionBuilder = $sectionBuilder;
        $this->commandBuilder = $commandBuilder;
    }

    /**
     * @param \Spryker\Install\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Install\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ): ConfigurationInterface {
        $this->commandLineArgumentContainer = $commandLineArgumentContainer;
        $this->commandLineOptionContainer = $commandLineOptionContainer;
        $this->output = $output;

        $this->configuration->setOutput($output);
        $this->configuration->setIsDryRun($commandLineOptionContainer->isDryRun());
        $this->configuration->setIsDebugMode($commandLineOptionContainer->isDebugMode());
        $this->configuration->setAskBeforeContinueAfterException($commandLineOptionContainer->askBeforeContinueOnException());

        $configuration = $this->configurationLoader->loadConfiguration($commandLineOptionContainer->getRecipe());

        $this->setEnv($configuration);
        $this->setStores($configuration);
        $this->setCommandTimeout($configuration);
        $this->setExecutableStores();
        $this->addStageToConfiguration($commandLineOptionContainer->getRecipe(), $configuration['sections']);

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
     * @param array $configuration
     *
     * @return void
     */
    protected function setCommandTimeout(array $configuration)
    {
        if (isset($configuration[static::CONFIG_COMMAND_TIMEOUT])) {
            $this->configuration->setCommandTimeout($configuration[static::CONFIG_COMMAND_TIMEOUT]);
        }
    }

    /**
     * @return void
     */
    protected function setExecutableStores()
    {
        $interactiveRequestedStores = $this->askForStoresToBeExecuted();
        if (count($interactiveRequestedStores) > 0) {
            $this->configuration->setExecutableStores($interactiveRequestedStores);

            return;
        }

        $requestedStore = $this->commandLineArgumentContainer->getStore();

        $arrayFilterCallback = function (string $store) use ($requestedStore) {
            return ($requestedStore === null || $store === $requestedStore);
        };
        $requestedStores = array_filter($this->configuration->getStores(), $arrayFilterCallback);

        $this->configuration->setExecutableStores($requestedStores);
    }

    /**
     * @return array
     */
    protected function askForStoresToBeExecuted(): array
    {
        if (!$this->commandLineOptionContainer->isInteractive() || !$this->configuration->getStores()) {
            return [];
        }

        $configuredStores = $this->configuration->getStores();
        array_unshift($configuredStores, static::ALL_STORES);

        $storesToBeExecuted = (array)$this->output->choice('Select stores to run install for (defaults to all)', $configuredStores, static::ALL_STORES);
        if ($storesToBeExecuted[0] === static::ALL_STORES) {
            return $configuredStores;
        }

        return $storesToBeExecuted;
    }

    /**
     * @param string $stageName
     * @param array $sections
     *
     * @return void
     */
    protected function addStageToConfiguration(string $stageName, array $sections)
    {
        $stage = new Stage($stageName);

        foreach ($this->filterSections($sections) as $sectionName => $commands) {
            $stage->addSection($this->buildSection($sectionName, $commands));
        }

        $this->configuration->setStage($stage);
    }

    /**
     * @param array $sections
     *
     * @return array
     */
    protected function filterSections(array $sections): array
    {
        return $this->getSectionFilter()->filter($sections);
    }

    /**
     * @return \Spryker\Install\Configuration\Filter\FilterInterface
     */
    protected function getSectionFilter(): FilterInterface
    {
        $filter = [
            new UnsetFilter(static::CONFIG_PRE_COMMAND),
            new UnsetFilter(static::CONFIG_POST_COMMAND),
        ];

        if ($this->commandLineOptionContainer->isInteractive()) {
            $filter[] = new InteractiveSectionExcludeFilter($this->output);

            return new FilterComposite($filter);
        }

        $filter[] = new SectionExcludeFilter(
            $this->commandLineOptionContainer->getIncludeExcluded(),
            $this->commandLineOptionContainer->getRequestedSections(),
            $this->commandLineOptionContainer->getRequestedGroups(),
            $this->commandLineOptionContainer->getExclude()
        );

        return new FilterComposite($filter);
    }

    /**
     * @param string $sectionName
     * @param array $sectionDefinition
     *
     * @return \Spryker\Install\Stage\Section\SectionInterface
     */
    protected function buildSection($sectionName, array $sectionDefinition): SectionInterface
    {
        $section = $this->sectionBuilder->buildSection($sectionName, $sectionDefinition);

        foreach ($this->filterCommands($sectionDefinition) as $commandName => $commandDefinition) {
            $section->addCommand($this->commandBuilder->buildCommand($commandName, $commandDefinition));
        }

        return $section;
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
     * @return \Spryker\Install\Configuration\Filter\FilterInterface
     */
    protected function getCommandFilter(): FilterInterface
    {
        $filter = [
            new UnsetFilter(static::CONFIG_PRE_COMMAND),
            new UnsetFilter(static::CONFIG_POST_COMMAND),
            new CommandExcludeFilter(
                $this->commandLineOptionContainer->getIncludeExcluded(),
                $this->commandLineOptionContainer->getRequestedGroups(),
                $this->commandLineOptionContainer->getExclude()
            ),
        ];

        return new FilterComposite($filter);
    }
}
