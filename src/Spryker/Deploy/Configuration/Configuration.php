<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration;

use Spryker\Deploy\Stage\Section\Command\CommandInterface;
use Spryker\Deploy\Stage\StageInterface;
use Spryker\Style\StyleInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Deploy\Stage\StageInterface
     */
    protected $stage;

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @var array
     */
    protected $stores = [];

    /**
     * @var array
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
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
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
     * @param \Spryker\Deploy\Stage\StageInterface $stage
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function setStage(StageInterface $stage): ConfigurationInterface
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return \Spryker\Deploy\Stage\StageInterface
     */
    public function getStage(): StageInterface
    {
        return $this->stage;
    }

    /**
     * @param bool $isDebugMode
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
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
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
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
     * @param array $env
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function setEnv(array $env): ConfigurationInterface
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }

    /**
     * @param array $stores
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function setStores(array $stores): ConfigurationInterface
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return array
     */
    public function getStores(): array
    {
        return $this->stores;
    }

    /**
     * @param array $executableStores
     *
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function setExecutableStores(array $executableStores): ConfigurationInterface
    {
        $this->executableStores = $executableStores;

        return $this;
    }

    /**
     * @return array
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
     * @return \Spryker\Deploy\Configuration\ConfigurationInterface
     */
    public function setAskBeforeContinueAfterException(bool $shouldAskBeforeContinueAfterException): ConfigurationInterface
    {
        $this->continueOnException = $shouldAskBeforeContinueAfterException;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Deploy\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface
    {
        list($section, $command) = explode('/', $name);

        return $this->stage->getSection($section)->getCommand($command);
    }
}
