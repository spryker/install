<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Console;

use Spryker\Setup\CommandLine\CommandLineArgumentContainer;
use Spryker\Setup\CommandLine\CommandLineOptionContainer;
use Spryker\Setup\Executable\ExecutableInterface;
use Spryker\Setup\SetupFacade;
use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\SectionInterface;
use Spryker\Setup\Stage\StageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SetupConsoleCommand extends Command
{
    const ARGUMENT_STAGE = 'stage';
    const ARGUMENT_STORE = 'store';

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
     * @var \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $commandExitCodes = [];

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('setup')
            ->setDescription('Run setup for a specified stage.')
            ->addArgument(static::ARGUMENT_STAGE, InputArgument::OPTIONAL, 'Name of the stage for which the setup should be executed.', 'development')
            ->addArgument(static::ARGUMENT_STORE, InputArgument::OPTIONAL, 'Name of the store for which the setup should be executed.', false)
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
        $this->output = $this->createOutput($input, $output);

        $configuration = $this->getConfiguration();

        $this->putEnv('FORCE_COLOR_MODE', true);
        $this->putEnvs($configuration->getEnv());

        foreach ($configuration->getStages() as $stage) {
            $this->executeStage($stage);
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected function createOutput(InputInterface $input, OutputInterface $output)
    {
        return new SymfonyStyle($input, $output);
    }

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
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
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    protected function executeSection(SectionInterface $section)
    {
        $this->output->text('<info>********************************************************</info>');
        $this->output->title(sprintf(' Section: <info>%s</info>', $section->getName()));
        $this->output->text('<info>********************************************************</info>');

        foreach ($section->getCommands() as $command) {
            $this->executeCommand($command);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    protected function executeCommand(CommandInterface $command)
    {
        if (!$this->shouldBeExecuted($command)) {
            return;
        }

        $this->putEnvs($command->getEnv());
        $this->runCommand($command);
        $this->unsetEnvs($command->getEnv());
        $this->putEnvs($this->getConfiguration()->getEnv());
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function shouldBeExecuted(CommandInterface $command)
    {
        if ($this->isDryRun()) {
            $this->output->comment('Dry-run: ' . $command->getName());

            return false;
        }

        if ($this->isConditionalCommand($command) && !$this->conditionMatched($command)) {
            return false;
        }

        return true;
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
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function isConditionalCommand(CommandInterface $command)
    {
        return count($command->getConditions()) > 0;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function conditionMatched(CommandInterface $command)
    {
        $matchedConditions = true;
        foreach ($command->getConditions() as $condition) {
            if (!$condition->match($this->commandExitCodes)) {
                $matchedConditions = false;
            }
        }
        return $matchedConditions;
    }

    /**
     * @return \Spryker\Setup\Configuration\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        if (!$this->configuration) {
            $commandLineArgumentContainer = new CommandLineArgumentContainer($this->getStageName());
            $commandLineOptionContainer = new CommandLineOptionContainer(
                $this->getSectionsToBeExecuted(),
                $this->getGroupsToBeExecuted(),
                $this->getExcludedStagesAndExcludedGroups(),
                $this->getIncludeExcluded(),
                $this->getIsInteractive()
            );

            $this->configuration = $this->getFacade()->buildConfiguration($commandLineArgumentContainer, $commandLineOptionContainer, $this->output);
        }

        return $this->configuration;
    }

    /**
     * @return string
     */
    protected function getStageName()
    {
        return $this->input->getArgument(static::ARGUMENT_STAGE);
    }

    /**
     * @return array
     */
    protected function getSectionsToBeExecuted()
    {
        return $this->getOptionAndComment(static::OPTION_SECTIONS, 'Setup will only run this section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getGroupsToBeExecuted()
    {
        return $this->getOptionAndComment(static::OPTION_GROUPS, 'Setup will only run this group(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getExcludedStagesAndExcludedGroups()
    {
        return $this->getOptionAndComment(static::OPTION_EXCLUDE, 'Setup will exclude this group(s) or section(s) "%s"');
    }

    /**
     * @return array
     */
    protected function getIncludeExcluded()
    {
        return $this->getOptionAndComment(static::OPTION_INCLUDE_EXCLUDED, 'Setup will include this excluded section(s) or command(s) "%s"');
    }

    /**
     * @return bool
     */
    protected function getIsInteractive()
    {
        return $this->input->getOption(static::OPTION_INTERACTIVE);
    }

    /**
     * @param string $optionKey
     * @param string $commentPattern
     *
     * @return mixed
     */
    protected function getOptionAndComment($optionKey, $commentPattern)
    {
        $option = $this->input->getOption($optionKey);

        if (count($option) > 0) {
            $this->output->comment(sprintf($commentPattern, implode(', ', $option)));
        }

        return $option;
    }

    /**
     * @param array $env
     *
     * @return void
     */
    protected function putEnvs(array $env)
    {
        foreach ($env as $key => $value) {
            $this->putEnv($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function putEnv($key, $value)
    {
        putenv(sprintf('%s=%s', $key, $value));
    }

    /**
     * @param array $env
     *
     * @return void
     */
    protected function unsetEnvs(array $env)
    {
        foreach (array_keys($env) as $key) {
            $this->unsetEnv($key);
        }
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function unsetEnv($key)
    {
        putenv($key);
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    protected function runCommand(CommandInterface $command)
    {
        $executable = $this->getFacade()->getExecutable($command);

        if (!$command->isStoreAware()) {
            $this->executeExecutable($executable, $command);

            return;
        }

        foreach ($this->getRequestedStores() as $store) {
            $this->putEnv('APPLICATION_STORE', $store);
            $this->executeExecutable($executable, $command, $store);
            $this->unsetEnv('APPLICATION_STORE');
        }
    }

    /**
     * @return array
     */
    protected function getRequestedStores()
    {
        $requestedStores = [];
        $requestedStore = $this->input->getArgument(static::ARGUMENT_STORE);

        foreach ($this->configuration->getStores() as $store) {
            if ($requestedStore && $store !== $requestedStore) {
                continue;
            }
            $requestedStores[] = $store;
        }

        return $requestedStores;
    }

    /**
     * @param \Spryker\Setup\Executable\ExecutableInterface $executable
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param null|string $store
     *
     * @return void
     */
    protected function executeExecutable(ExecutableInterface $executable, CommandInterface $command, $store = null)
    {
        $commandInfo = sprintf('Command: <info>%s</info>', $command->getName());
        $storeInfo = ($store) ?  sprintf(' for <info>%s</info> store', $store) : '';

        $this->output->section($commandInfo . $storeInfo);
        $this->output->note(sprintf('CLI call: %s', $command->getExecutable()));

        $exitCode = $executable->execute($this->output);
        $this->commandExitCodes[$command->getName()] = $exitCode;
    }

    /**
     * @return \Spryker\Setup\SetupFacade
     */
    protected function getFacade()
    {
        return new SetupFacade();
    }
}
