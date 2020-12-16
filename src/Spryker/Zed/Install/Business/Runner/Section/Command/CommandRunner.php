<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Runner\Section\Command;

use Spryker\Zed\Install\Business\Configuration\ConfigurationInterface;
use Spryker\Zed\Install\Business\Exception\InstallException;
use Spryker\Zed\Install\Business\Executable\ExecutableFactory;
use Spryker\Zed\Install\Business\Executable\ExecutableInterface;
use Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface;
use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;

class CommandRunner implements CommandRunnerInterface
{
    /**
     * @var \Spryker\Zed\Install\Business\Executable\ExecutableFactory
     */
    protected $executableFactory;

    /**
     * @var \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface
     */
    protected $environmentHelper;

    /**
     * @var array
     */
    protected $commandExitCodes = [];

    /**
     * @param \Spryker\Zed\Install\Business\Executable\ExecutableFactory $executableFactory
     * @param \Spryker\Zed\Install\Business\Runner\Environment\EnvironmentHelperInterface $environmentHelper
     */
    public function __construct(ExecutableFactory $executableFactory, EnvironmentHelperInterface $environmentHelper)
    {
        $this->executableFactory = $executableFactory;
        $this->environmentHelper = $environmentHelper;
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @throws \Spryker\Zed\Install\Business\Exception\InstallException
     *
     * @return void
     */
    public function run(CommandInterface $command, ConfigurationInterface $configuration): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runPreCommand(CommandInterface $command, ConfigurationInterface $configuration): void
    {
        if ($command->hasPreCommand()) {
            $this->run($configuration->findCommand($command->getPreCommand()), $configuration);
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runCommand(CommandInterface $command, ConfigurationInterface $configuration): void
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     *
     * @return void
     */
    protected function runPostCommand(CommandInterface $command, ConfigurationInterface $configuration): void
    {
        if ($command->hasPostCommand()) {
            $this->run($configuration->findCommand($command->getPostCommand()), $configuration);
        }
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     *
     * @return bool
     */
    protected function isConditionalCommand(CommandInterface $command): bool
    {
        return (count($command->getConditions()) > 0);
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
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
     * @param \Spryker\Zed\Install\Business\Executable\ExecutableInterface $executable
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface $configuration
     * @param string|null $store
     *
     * @return void
     */
    protected function executeExecutable(
        ExecutableInterface $executable,
        CommandInterface $command,
        ConfigurationInterface $configuration,
        ?string $store = null
    ): void {
        $configuration->getOutput()->startCommand($command, $store);

        $exitCode = $executable->execute($configuration->getOutput());
        $this->commandExitCodes[$command->getName()] = $exitCode;

        $configuration->getOutput()->endCommand($command, $exitCode, $store);
    }
}
