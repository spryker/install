<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Console;

use Spryker\Command\CommandInterface;
use Spryker\Command\CommandLine\CommandLineCommand;
use Spryker\Configuration\ConfigurationBuilder;
use Spryker\Configuration\ConfigurationLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetupConsoleCommand extends Command
{

    const ARGUMENT_STAGE = 'stage';
    const ARGUMENT_CONFIGURATION = 'configuration';

    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_DRY_RUN_SHORT = 'd';

    const OPTION_SECTIONS = 'sections';
    const OPTION_SECTIONS_SHORT = 's';

    const OPTION_GROUPS = 'groups';
    const OPTION_GROUPS_SHORT = 'g';

    const OPTION_EXCLUDE = 'exclude';
    const OPTION_EXCLUDE_SHORT = 'x';

    const OPTION_INTERACTIVE = 'interactive';
    const OPTION_INTERACTIVE_SHORT = 'i';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('setup')
            ->setDescription('Run setup for a specified stage.')
            ->addArgument(static::ARGUMENT_STAGE, InputArgument::OPTIONAL, 'Name of the stage for which setup should be executed.', 'development')
            ->addArgument(static::ARGUMENT_CONFIGURATION, InputArgument::OPTIONAL, 'Path to a configuration file to be used.', '.spryker/spryker.yml')
            ->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Only output what would be executed.')
            ->addOption(static::OPTION_SECTIONS, static::OPTION_SECTIONS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of stages to be executed.')
            ->addOption(static::OPTION_GROUPS, static::OPTION_GROUPS_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of groups to be executed. If command has no group(s) it will not be executed when this option is set.')
            ->addOption(static::OPTION_EXCLUDE, static::OPTION_EXCLUDE_SHORT, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Names of stages or groups to be excluded from execution.')
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
        $style = new SymfonyStyle($input, $output);

        $configuration = $this->getConfiguration($input, $style);

        $isDryRun = $input->getOption(static::OPTION_DRY_RUN);

        foreach ($configuration->getStages() as $stage) {
            putenv(sprintf('APPLICATION_ENV=%s', $stage->getName()));

            $style->title(sprintf('Start setup for stage: <info>%s</info>', $stage->getName()));

            foreach ($stage->getSections() as $section) {
                $style->title(sprintf('Section: <info>%s</info>', $section->getName()));

                foreach ($section->getCommands() as $command) {
                    if ($isDryRun) {
                        $style->comment('Dry-run: ' . $command->getName());
                        continue;
                    }

                    $executable = $command->getExecutable();
                    if (class_exists($executable)) {
                        $executable = new $executable();
                        if ($executable instanceof CommandInterface) {
                            $executable->execute($style);
                        }
                    }
                    if (is_string($executable)) {
                        $executable = new CommandLineCommand($command);
                        $executable->execute($style);
                    }
                }
            }
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     *
     * @return \Spryker\Configuration\ConfigurationInterface
     */
    protected function getConfiguration(InputInterface $input, SymfonyStyle $style)
    {
        $configurationName = $input->getArgument(static::ARGUMENT_CONFIGURATION);
        $pathToConfiguration = SPRYKER_ROOT . '/' . $configurationName;

        $sectionsToBeExecuted = $this->getSectionsToBeExecuted($input, $style);
        $groupsToBeExecuted = $this->getGroupsToBeExecuted($input, $style);
        $excludedStagesAndExcludedGroups = $this->getExcludedStagesAndExcludedGroups($input, $style);
        $isInteractive = $input->getOption(static::OPTION_INTERACTIVE);

        $configurationLoader = new ConfigurationLoader($pathToConfiguration);
        $configurationBuilder = new ConfigurationBuilder(
            $configurationLoader,
            $sectionsToBeExecuted,
            $groupsToBeExecuted,
            $excludedStagesAndExcludedGroups,
            $isInteractive,
            $style
        );

        $stageName = $input->getArgument(static::ARGUMENT_STAGE);

        return $configurationBuilder->buildConfiguration($stageName);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     *
     * @return array
     */
    protected function getSectionsToBeExecuted(InputInterface $input, SymfonyStyle $style)
    {
        $sectionsToBeExecuted = $input->getOption(static::OPTION_SECTIONS);
        if (count($sectionsToBeExecuted) > 0) {
            $style->comment(sprintf('Setup will only run this section(s) "%s"', implode(', ', $sectionsToBeExecuted)));
        }
        return $sectionsToBeExecuted;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     *
     * @return array
     */
    protected function getGroupsToBeExecuted(InputInterface $input, SymfonyStyle $style)
    {
        $groupsToBeExecuted = $input->getOption(static::OPTION_GROUPS);
        if (count($groupsToBeExecuted) > 0) {
            $style->comment(sprintf('Setup will only run this group(s) "%s"', implode(', ', $groupsToBeExecuted)));
        }

        return $groupsToBeExecuted;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     *
     * @return array
     */
    protected function getExcludedStagesAndExcludedGroups(InputInterface $input, SymfonyStyle $style)
    {
        $excludedStagesOrGroups = $input->getOption(static::OPTION_EXCLUDE);
        if (count($excludedStagesOrGroups) > 0) {
            $style->comment(sprintf('Setup will exclude this group(s) or section(s) "%s"', implode(', ', $excludedStagesOrGroups)));
        }

        return $excludedStagesOrGroups;
    }

}
