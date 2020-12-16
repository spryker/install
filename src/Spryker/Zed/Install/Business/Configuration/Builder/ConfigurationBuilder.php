<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Builder;

use Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilderInterface;
use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Configuration\Filter\CommandExcludeFilter;
use Spryker\Zed\Install\Business\Configuration\Filter\FilterComposite;
use Spryker\Zed\Install\Business\Configuration\Filter\FilterInterface;
use Spryker\Zed\Install\Business\Configuration\Filter\InteractiveSectionExcludeFilter;
use Spryker\Zed\Install\Business\Configuration\Filter\SectionExcludeFilter;
use Spryker\Zed\Install\Business\Configuration\Filter\UnsetFilter;
use Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoaderInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;
use Spryker\Zed\Install\Business\Stage\Stage;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer;
use Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer;
use Spryker\Zed\Install\Communication\Style\StyleInterface;

class ConfigurationBuilder implements ConfigurationBuilderInterface
{
    public const CONFIG_EXCLUDED = 'excluded';
    public const CONFIG_ENV = 'env';
    public const CONFIG_STORES = 'stores';
    public const CONFIG_COMMAND_TIMEOUT = 'command-timeout';
    public const CONFIG_GROUPS = 'groups';
    public const CONFIG_CONDITIONS = 'conditions';
    public const CONFIG_PRE_COMMAND = 'pre';
    public const CONFIG_POST_COMMAND = 'post';
    public const ALL_STORES = 'all';

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoaderInterface
     */
    protected $configurationLoader;

    /**
     * @var \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer
     */
    protected $commandLineArgumentContainer;

    /**
     * @var \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer
     */
    protected $commandLineOptionContainer;

    /**
     * @var \Spryker\Zed\Install\Communication\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilderInterface
     */
    protected $sectionBuilder;

    /**
     * @var \Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilderInterface
     */
    protected $commandBuilder;

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\Loader\ConfigurationLoaderInterface $configurationLoader
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     * @param \Spryker\Zed\Install\Business\Configuration\Builder\Section\SectionBuilderInterface $sectionBuilder
     * @param \Spryker\Zed\Install\Business\Configuration\Builder\Section\Command\CommandBuilderInterface $commandBuilder
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
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineArgumentContainer $commandLineArgumentContainer
     * @param \Spryker\Zed\Install\Communication\CommandLine\CommandLineOptionContainer $commandLineOptionContainer
     * @param \Spryker\Zed\Install\Communication\Style\StyleInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
    protected function setEnv(array $configuration): void
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
    protected function setStores(array $configuration): void
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
    protected function setCommandTimeout(array $configuration): void
    {
        if (isset($configuration[static::CONFIG_COMMAND_TIMEOUT])) {
            $this->configuration->setCommandTimeout($configuration[static::CONFIG_COMMAND_TIMEOUT]);
        }
    }

    /**
     * @return void
     */
    protected function setExecutableStores(): void
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
    protected function addStageToConfiguration(string $stageName, array $sections): void
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
     * @return \Spryker\Zed\Install\Business\Configuration\Filter\FilterInterface
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
     * @return \Spryker\Zed\Install\Business\Stage\Section\SectionInterface
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
    protected function filterCommands(array $commands): array
    {
        return $this->getCommandFilter()->filter($commands);
    }

    /**
     * @return \Spryker\Zed\Install\Business\Configuration\Filter\FilterInterface
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
