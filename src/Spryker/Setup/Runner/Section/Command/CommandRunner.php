<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Runner\Section\Command;

use Spryker\Setup\Configuration\ConfigurationInterface;
use Spryker\Setup\Exception\SetupException;
use Spryker\Setup\Executable\ExecutableFactory;
use Spryker\Setup\Executable\ExecutableInterface;
use Spryker\Setup\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Setup\Stage\Section\Command\CommandInterface;

class CommandRunner implements CommandRunnerInterface
{
    /**
     * @var \Spryker\Setup\Executable\ExecutableFactory
     */
    protected $executableFactory;

    /**
     * @var \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @var array
     */
    protected $commandExitCodes = [];

    /**
     * @param \Spryker\Setup\Executable\ExecutableFactory $executableFactory
     * @param \Spryker\Setup\Runner\Environment\EnvironmentHelperInterface $environmentHelper
     */
    public function __construct(ExecutableFactory $executableFactory, EnvironmentHelperInterface $environmentHelper)
    {
        $this->executableFactory = $executableFactory;
        $this->environmentHelper = $environmentHelper;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Setup\Exception\SetupException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration)
    {
        if (!$this->shouldBeExecuted($command, $configuration)) {
            return;
        }

        $this->environmentHelper->putEnvs($command->getEnv());

        $this->runPreCommand($command, $configuration);
        $this->runCommand($command, $configuration);
        $this->runPostCommand($command, $configuration);

        $this->environmentHelper->unsetEnvs($command->getEnv());
        $this->environmentHelper->putEnvs($configuration->getEnv());

        if ($configuration->isDebugMode() && !$configuration->getOutput()->confirm('Should setup resume?')) {
            throw new SetupException('Setup aborted...');
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runPreCommand(CommandInterface $command, ConfigurationInterface $configuration)
    {
        if ($command->hasPreCommand()) {
            $this->run($configuration->findCommand($command->getPreCommand()), $configuration);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runCommand(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $executable = $this->executableFactory->createExecutableFromCommand($command);

        if (!$command->isStoreAware()) {
            $this->executeExecutable($executable, $command, $configuration);

            return;
        }

        foreach ($configuration->getExecutableStores() as $store) {
            $this->environmentHelper->putEnv('APPLICATION_STORE', $store);
            $this->executeExecutable($executable, $command, $configuration, $store);
            $this->environmentHelper->unsetEnv('APPLICATION_STORE');
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runPostCommand(CommandInterface $command, ConfigurationInterface $configuration)
    {
        if ($command->hasPostCommand()) {
            $this->run($configuration->findCommand($command->getPostCommand()), $configuration);
        }
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     *
     * @return bool
     */
    protected function shouldBeExecuted(CommandInterface $command, ConfigurationInterface $configuration)
    {
        if ($configuration->isDryRun()) {
            $configuration->getOutput()->note('Dry-run: ' . $command->getName());

            return false;
        }

        if ($command->isExcluded()) {
            return false;
        }

        if ($this->hasConditionAndConditionNotMatched($command)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function hasConditionAndConditionNotMatched(CommandInterface $command)
    {
        if (!$this->isConditionalCommand($command) || $this->conditionMatched($command)) {
            return false;
        }

        return true;
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
     * @param \Spryker\Setup\Executable\ExecutableInterface $executable
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Setup\Configuration\ConfigurationInterface $configuration
     * @param null|string $store
     *
     * @return void
     */
    protected function executeExecutable(ExecutableInterface $executable, CommandInterface $command, ConfigurationInterface $configuration, $store = null)
    {
        $commandInfo = sprintf('Command: <info>%s</info>', $command->getName());
        $storeInfo = ($store) ?  sprintf(' for <info>%s</info> store', $store) : '';
        $executedInfo = sprintf(' <fg=yellow>[%s]</>', $command->getExecutable());

        $configuration->getOutput()->text($commandInfo . $storeInfo . $executedInfo);
        $configuration->getOutput()->newLine();

        $exitCode = $executable->execute($configuration->getOutput());

        $this->commandExitCodes[$command->getName()] = $exitCode;

        $configuration->getOutput()->note(sprintf('Done, exit code <fg=green>%s</>', $exitCode));
        $configuration->getOutput()->newLine();
    }
}
