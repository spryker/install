<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Stage\StageInterface;
use Spryker\Style\StyleInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Setup\Stage\StageInterface
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
     * @return $this
     */
    public function setOutput(StyleInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return \Spryker\Style\StyleInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param bool $isDebugMode
     *
     * @return $this
     */
    public function setIsDebugMode($isDebugMode)
    {
        $this->isDebugMode = $isDebugMode;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->isDebugMode;
    }

    /**
     * @param bool $isDryRun
     *
     * @return $this
     */
    public function setIsDryRun($isDryRun)
    {
        $this->isDryRun = $isDryRun;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDryRun()
    {
        return $this->isDryRun;
    }

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores)
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return array
     */
    public function getStores()
    {
        return $this->stores;
    }

    /**
     * @param array $executableStores
     *
     * @return $this
     */
    public function setExecutableStores(array $executableStores)
    {
        $this->executableStores = $executableStores;

        return $this;
    }

    /**
     * @return array
     */
    public function getExecutableStores()
    {
        return $this->executableStores;
    }

    /**
     * @return bool
     */
    public function shouldAskBeforeContinueAfterException()
    {
        return $this->continueOnException;
    }

    /**
     * @param bool $shouldAskBeforeContinueAfterException
     *
     * @return $this
     */
    public function setAskBeforeContinueAfterException($shouldAskBeforeContinueAfterException)
    {
        $this->continueOnException = $shouldAskBeforeContinueAfterException;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function findCommand($name)
    {
        list($section, $command) = explode('/', $name);

        return $this->stage->getSection($section)->getCommand($command);
    }
}
