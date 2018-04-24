<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Runner\Section\Command;

use Spryker\Install\Configuration\ConfigurationInterface;
use Spryker\Install\Exception\InstallException;
use Spryker\Install\Executable\ExecutableFactory;
use Spryker\Install\Executable\ExecutableInterface;
use Spryker\Install\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Install\Stage\Section\Command\CommandInterface;

class CommandRunner implements CommandRunnerInterface
{
    /**
     * @var \Spryker\Install\Executable\ExecutableFactory
     */
    protected $executableFactory;

    /**
     * @var \Spryker\Install\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @var array
     */
    protected $commandExitCodes = [];

    /**
     * @param \Spryker\Install\Executable\ExecutableFactory $executableFactory
     * @param \Spryker\Install\Runner\Environment\EnvironmentHelperInterface $environmentHelper
     */
    public function __construct(ExecutableFactory $executableFactory, EnvironmentHelperInterface $environmentHelper)
    {
        $this->executableFactory = $executableFactory;
        $this->environmentHelper = $environmentHelper;
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Install\Exception\InstallException
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

        if ($configuration->isDebugMode() && !$configuration->getOutput()->confirm('Should install resume?', true)) {
            throw new InstallException('Install aborted...');
        }
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runCommand(CommandInterface $command, ConfigurationInterface $configuration)
    {
        $executable = $this->executableFactory->createExecutableFromCommand($command, $configuration);

        if (!$command->isStoreAware()) {
            $this->executeExecutable($executable, $command, $configuration);

            return;
        }

        foreach ($this->getExecutableStoresForCommand($command, $configuration) as $store) {
            $this->environmentHelper->putEnv('APPLICATION_STORE', $store);
            $this->executeExecutable($executable, $command, $configuration, $store);
            $this->environmentHelper->unsetEnv('APPLICATION_STORE');
        }
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return string[]
     */
    protected function getExecutableStoresForCommand(CommandInterface $command, ConfigurationInterface $configuration): array
    {
        if (!$command->hasStores()) {
            return $configuration->getExecutableStores();
        }

        return array_intersect($configuration->getExecutableStores(), $command->getStores());
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
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
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     *
     * @return bool
     */
    protected function shouldBeExecuted(CommandInterface $command, ConfigurationInterface $configuration): bool
    {
        if ($configuration->isDryRun()) {
            $configuration->getOutput()->dryRunCommand($command);

            return false;
        }

        if ($command->isExcluded() || $this->hasConditionAndConditionNotMatched($command)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function hasConditionAndConditionNotMatched(CommandInterface $command): bool
    {
        if (!$this->isConditionalCommand($command) || $this->conditionMatched($command)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function isConditionalCommand(CommandInterface $command): bool
    {
        return (count($command->getConditions()) > 0);
    }

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function conditionMatched(CommandInterface $command): bool
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
     * @param \Spryker\Install\Executable\ExecutableInterface $executable
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Install\Configuration\ConfigurationInterface $configuration
     * @param null|string $store
     *
     * @return void
     */
    protected function executeExecutable(
        ExecutableInterface $executable,
        CommandInterface $command,
        ConfigurationInterface $configuration,
        ?string $store = null
    ) {
        $configuration->getOutput()->startCommand($command, $store);

        $exitCode = $executable->execute($configuration->getOutput());
        $this->commandExitCodes[$command->getName()] = $exitCode;

        $configuration->getOutput()->endCommand($command, $exitCode, $store);
    }
}
