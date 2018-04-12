<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration;

use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Install\Stage\StageInterface;
use Spryker\Style\StyleInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Install\Stage\StageInterface
     */
    protected $stage;

    /**
     * @var string[]
     */
    protected $env = [];

    /**
     * @var string[]
     */
    protected $stores = [];

    /**
     * @var int
     */
    protected $commandTimeout;

    /**
     * @var string[]
     */
    protected $executableStores = [];

    /**
     * @var bool
     */
    protected $isDryRun;

    /**
     * @var bool
     */
    protected $isDebugMode;

    /**
     * @var bool
     */
    protected $continueOnException;

    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setOutput(StyleInterface $output): ConfigurationInterface
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return \Spryker\Style\StyleInterface
     */
    public function getOutput(): StyleInterface
    {
        return $this->output;
    }

    /**
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setStage(StageInterface $stage): ConfigurationInterface
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return \Spryker\Install\Stage\StageInterface
     */
    public function getStage(): StageInterface
    {
        return $this->stage;
    }

    /**
     * @param bool $isDebugMode
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setIsDebugMode(bool $isDebugMode): ConfigurationInterface
    {
        $this->isDebugMode = $isDebugMode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebugMode(): bool
    {
        return $this->isDebugMode;
    }

    /**
     * @param bool $isDryRun
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setIsDryRun(bool $isDryRun): ConfigurationInterface
    {
        $this->isDryRun = $isDryRun;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->isDryRun;
    }

    /**
     * @param string[] $env
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setEnv(array $env): ConfigurationInterface
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * @param string[] $stores
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setStores(array $stores): ConfigurationInterface
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getStores(): array
    {
        return $this->stores;
    }

    /**
     * @param int $commandTimeout
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setCommandTimeout(int $commandTimeout): ConfigurationInterface
    {
        $this->commandTimeout = $commandTimeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommandTimeout(): ?int
    {
        return $this->commandTimeout;
    }

    /**
     * @param string[] $executableStores
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setExecutableStores(array $executableStores): ConfigurationInterface
    {
        $this->executableStores = $executableStores;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExecutableStores(): array
    {
        return $this->executableStores;
    }

    /**
     * @return bool
     */
    public function shouldAskBeforeContinueAfterException(): bool
    {
        return $this->continueOnException;
    }

    /**
     * @param bool $shouldAskBeforeContinueAfterException
     *
     * @return \Spryker\Install\Configuration\ConfigurationInterface
     */
    public function setAskBeforeContinueAfterException(bool $shouldAskBeforeContinueAfterException): ConfigurationInterface
    {
        $this->continueOnException = $shouldAskBeforeContinueAfterException;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface
    {
        list($section, $command) = explode('/', $name);

        return $this->stage->getSection($section)->getCommand($command);
    }
}
