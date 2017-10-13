<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Console;

use Spryker\Command\CommandInterface;
use Spryker\Command\CommandLine\CommandLineCommand;
use Spryker\Configuration\Command\CommandInterface as ConfigurationCommandInterface;
use Spryker\Configuration\ConfigurationBuilder;
use Spryker\Configuration\ConfigurationLoader;
use Spryker\Configuration\Section\SectionInterface;
use Spryker\Configuration\Stage\StageInterface;
use Spryker\Configuration\Validator\ConfigurationValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetupConsoleCommand extends Command
{
    const ARGUMENT_STAGE = 'stage';

    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_DRY_RUN_SHORT = 'd';

    const OPTION_SECTIONS = 'sections';
    const OPTION_SECTIONS_SHORT = 's';

    const OPTION_GROUPS = 'groups';
    const OPTION_GROUPS_SHORT = 'g';

    const OPTION_EXCLUDE = 'exclude';
    const OPTION_EXCLUDE_SHORT = 'x';

    const OPTION_INCLUDE_EXCLUDED = 'include-excluded';
    const OPTION_INCLUDE_EXCLUDED_SHORT = 'a';

    const OPTION_INTERACTIVE = 'interactive';
    const OPTION_INTERACTIVE_SHORT = 'i';

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * @var bool
     */
    protected $isDryRun;

    /**
     * @var \Spryker\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('setup')
            ->setDescription('Run setup for a specified stage.')
            ->addArgument(static::ARGUMENT_STAGE, InputArgument::OPTIONAL, 'Name of the stage for which setup should be executed.', 'development')
            ->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Only output what would be executed.')
            ->addOption(static::OPTION_SECTIONS, static::OPTION_SECTIONS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of stages to be executed.')
            ->addOption(static::OPTION_GROUPS, static::OPTION_GROUPS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of groups to be executed. If command has no group(s) it will not be executed when this option is set.')
            ->addOption(static::OPTION_EXCLUDE, static::OPTION_EXCLUDE_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of stages or groups to be excluded from execution.')
            ->addOption(static::OPTION_INCLUDE_EXCLUDED, static::OPTION_INCLUDE_EXCLUDED_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Add commands/stages which are marked as excluded in the configuration.')
            ->addOption(static::OPTION_INTERACTIVE, static::OPTION_INTERACTIVE_SHORT, InputOption::VALUE_NONE, 'Will ask prior to each step if it should be executed or not.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $configuration = $this->getConfiguration();

        $this->putEnv($configuration->getEnv());

        foreach ($configuration->getStages() as $stage) {
            $this->executeStage($stage);
        }
    }

    /**
     * @param \Spryker\Configuration\Stage\StageInterface $stage
     *
     * @return void
     */
    protected function executeStage(StageInterface $stage)
    {
        $this->output->title(sprintf('Start setup: <info>%s</info>', $stage->getName()));

        foreach ($stage->getSections() as $section) {
            $this->executeSection($section);
        }
    }

    /**
     * @param \Spryker\Configuration\Section\SectionInterface $section
     *
     * @return void
     */
    protected function executeSection(SectionInterface $section)
    {
        $this->output->title(sprintf('Section: <info>%s</info>', $section->getName()));

        foreach ($section->getCommands() as $command) {
            $this->executeCommand($command);
        }
    }

    /**
     * @param \Spryker\Configuration\Command\CommandInterface $command
     *
     * @return void
     */
    protected function executeCommand(ConfigurationCommandInterface $command)
    {
        if ($this->isDryRun()) {
            $this->output->comment('Dry-run: ' . $command->getName());

            return;
        }

        $this->putEnv($command->getEnv());

        $this->executeExecutable($command);

        $this->unsetEnv($command->getEnv());
        $this->putEnv($this->configuration->getEnv());
    }

    /**
     * @return bool
     */
    protected function isDryRun()
    {
        if ($this->isDryRun === null) {
            $this->isDryRun = $this->input->getOption(static::OPTION_DRY_RUN);
        }

        return $this->isDryRun;
    }

    /**
     * @return \Spryker\Configuration\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        if (!$this->configuration) {
            $this->configuration = $this->buildConfiguration();
        }

        return $this->configuration;
    }

    /**
     * @return \Spryker\Configuration\Configuration|\Spryker\Configuration\ConfigurationInterface
     */
    protected function buildConfiguration()
    {
        return $this->getConfigurationBuilder()->buildConfiguration($this->getStageName());
    }

    /**
     * @return \Spryker\Configuration\ConfigurationBuilder
     */
    protected function getConfigurationBuilder()
    {
        return new ConfigurationBuilder(
            new ConfigurationLoader($this->getStageName()),
            new ConfigurationValidator(),
            $this->getSectionsToBeExecuted(),
            $this->getGroupsToBeExecuted(),
            $this->getExcludedStagesAndExcludedGroups(),
            $this->getIncludeExcluded(),
            $this->input->getOption(static::OPTION_INTERACTIVE),
            $this->output
        );
    }

    /**
     * @return array
     */
    protected function getSectionsToBeExecuted()
    {
        $sectionsToBeExecuted = $this->input->getOption(static::OPTION_SECTIONS);

        if (count($sectionsToBeExecuted) > 0) {
            $this->output->comment(sprintf('Setup will only run this section(s) "%s"', implode(', ', $sectionsToBeExecuted)));
        }

        return $sectionsToBeExecuted;
    }

    /**
     * @return array
     */
    protected function getGroupsToBeExecuted()
    {
        $groupsToBeExecuted = $this->input->getOption(static::OPTION_GROUPS);

        if (count($groupsToBeExecuted) > 0) {
            $this->output->comment(sprintf('Setup will only run this group(s) "%s"', implode(', ', $groupsToBeExecuted)));
        }

        return $groupsToBeExecuted;
    }

    /**
     * @return array
     */
    protected function getExcludedStagesAndExcludedGroups()
    {
        $excludedStagesOrGroups = $this->input->getOption(static::OPTION_EXCLUDE);

        if (count($excludedStagesOrGroups) > 0) {
            $this->output->comment(sprintf('Setup will exclude this group(s) or section(s) "%s"', implode(', ', $excludedStagesOrGroups)));
        }

        return $excludedStagesOrGroups;
    }

    /**
     * @return array
     */
    protected function getIncludeExcluded()
    {
        $includeExcluded = $this->input->getOption(static::OPTION_INCLUDE_EXCLUDED);

        if (count($includeExcluded) > 0) {
            $this->output->comment(sprintf('Setup will include this excluded section(s) or command(s) "%s"', implode(', ', $includeExcluded)));
        }

        return $includeExcluded;
    }

    /**
     * @param array $env
     *
     * @return void
     */
    protected function putEnv(array $env)
    {
        foreach ($env as $key => $value) {
            putenv(sprintf('%s=%s', $key, $value));
        }
    }

    /**
     * @param array $env
     *
     * @return void
     */
    protected function unsetEnv(array $env)
    {
        foreach (array_keys($env) as $key) {
            putenv($key);
        }
    }

    /**
     * @return string
     */
    protected function getStageName()
    {
        $stageName = $this->input->getArgument(static::ARGUMENT_STAGE);

        return $stageName;
    }

    /**
     * @param \Spryker\Configuration\Command\CommandInterface $command
     *
     * @return void
     */
    protected function executeExecutable(ConfigurationCommandInterface $command)
    {
        $executable = $command->getExecutable();

        if (class_exists($executable)) {
            $executable = new $executable();
            if ($executable instanceof CommandInterface) {
                $executable->execute($this->output);
            }
        }

        if (is_string($executable)) {
            $executable = new CommandLineCommand($command);
            $executable->execute($this->output);
        }
    }
}
