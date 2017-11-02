<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Builder;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Configuration\Filter\CommandExcludeFilter;
use Spryker\Setup\Configuration\Filter\FilterComposite;
use Spryker\Setup\Configuration\Filter\InteractiveSectionExcludeFilter;
use Spryker\Setup\Configuration\Filter\SectionExcludeFilter;
use Spryker\Setup\Configuration\Filter\UnsetFilter;
use Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Setup\Stage\Stage;
use Symfony\Component\Console\Style\StyleInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    const CONFIG_EXCLUDED = 'excluded';
    const CONFIG_ENV = 'env';
    const CONFIG_STORES = 'stores';
    const CONFIG_GROUPS = 'groups';
    const CONFIG_CONDITIONS = 'conditions';
    const CONFIG_PRE_COMMAND = 'pre';
    const CONFIG_POST_COMMAND = 'post';

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
     * @var \Symfony\Component\Console\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var \Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected $sectionBuilder;

    /**
     * @var \Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected $commandBuilder;

    /**
     * @param \Spryker\Setup\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     * @param \Spryker\Setup\Configuration\Builder\Section\SectionBuilderInterface $sectionBuilder
     * @param \Spryker\Setup\Configuration\Builder\Section\Command\CommandBuilderInterface $commandBuilder
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
     * @param \Spryker\Setup\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Setup\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    public function buildConfiguration(
        CommandLineArgumentContainer $commandLineArgumentContainer,
        CommandLineOptionContainer $commandLineOptionContainer,
        StyleInterface $output
    ) {
        $this->commandLineArgumentContainer = $commandLineArgumentContainer;
        $this->commandLineOptionContainer = $commandLineOptionContainer;
        $this->output = $output;

        $this->configuration->setOutput($output);
        $this->configuration->setIsDryRun($commandLineOptionContainer->isDryRun());
        $this->configuration->setIsDebugMode($commandLineOptionContainer->isDebugMode());
        $this->configuration->setAskBeforeContinueAfterException($commandLineOptionContainer->askBeforeContinueOnException());

        $configuration = $this->configurationLoader->loadConfiguration($commandLineArgumentContainer->getStage());

        $this->setEnv($configuration);
        $this->setStores($configuration);
        $this->setExecutableStores();
        $this->addStageToConfiguration($commandLineArgumentContainer->getStage(), $configuration['sections']);

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

        $arrayFilterCallback = function ($store) use ($requestedStore) {
            return (!$requestedStore || $store === $requestedStore);
        };
        $requestedStores = array_filter($this->configuration->getStores(), $arrayFilterCallback);

        $this->configuration->setExecutableStores($requestedStores);
    }

    /**
     * @return array
     */
    protected function askForStoresToBeExecuted()
    {
        if (!$this->commandLineOptionContainer->isInteractive() || !$this->configuration->getStores()) {
            return [];
        }

        $configuredStores = $this->configuration->getStores();
        array_unshift($configuredStores, 'all');

        $storesToBeExecuted = (array)$this->output->choice('Select stores to run setup for (defaults to all)', $configuredStores, 'all');
        if ($storesToBeExecuted[0] === 'all') {
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
    protected function addStageToConfiguration($stageName, array $sections)
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
    protected function filterSections(array $sections)
    {
        return $this->getSectionFilter()->filter($sections);
    }

    /**
     * @return \Spryker\Setup\Configuration\Filter\FilterInterface
     */
    protected function getSectionFilter()
    {
        $filter = [
            new UnsetFilter('pre'),
            new UnsetFilter('post'),
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
     * @return \Spryker\Setup\Stage\Section\SectionInterface
     */
    protected function buildSection($sectionName, array $sectionDefinition)
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
     * @return \Spryker\Setup\Configuration\Filter\FilterInterface
     */
    protected function getCommandFilter()
    {
        $filter = [
            new UnsetFilter('pre'),
            new UnsetFilter('post'),
            new CommandExcludeFilter(
                $this->commandLineOptionContainer->getIncludeExcluded(),
                $this->commandLineOptionContainer->getRequestedGroups(),
                $this->commandLineOptionContainer->getExclude()
            ),
        ];

        return new FilterComposite($filter);
    }
}
