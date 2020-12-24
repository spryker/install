<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration;

use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Stage\StageInterface;
use Spryker\Zed\Install\Business\Style\StyleInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    protected $output;

    /**
     * @var \Spryker\Zed\Install\Business\Stage\StageInterface
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
     * @var string[]
     */
    protected $sections;

    /**
     * @var string[]
     */
    protected $groups;

    /**
     * @var string[]
     */
    protected $exclude;

    /**
     * @var string[]
     */
    protected $includeExcluded;

    /**
     * @param \Spryker\Zed\Install\Business\Style\StyleInterface $output
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function setOutput(StyleInterface $output): ConfigurationInterface
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    public function getOutput(): StyleInterface
    {
        return $this->output;
    }

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function setStage(StageInterface $stage): ConfigurationInterface
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Install\Business\Stage\StageInterface
     */
    public function getStage(): StageInterface
    {
        return $this->stage;
    }

    /**
     * @param bool $isDebugMode
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function setCommandTimeout(int $commandTimeout): ConfigurationInterface
    {
        $this->commandTimeout = $commandTimeout;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCommandTimeout(): ?int
    {
        return $this->commandTimeout;
    }

    /**
     * @param string[] $executableStores
     *
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
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
     * @return \Spryker\Zed\Install\Business\Configuration\ConfigurationInterface
     */
    public function setAskBeforeContinueAfterException(bool $shouldAskBeforeContinueAfterException): ConfigurationInterface
    {
        $this->continueOnException = $shouldAskBeforeContinueAfterException;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface
    {
        [$section, $command] = explode('/', $name);

        return $this->stage->getSection($section)->getCommand($command);
    }

    /**
     * @param string[] $sections
     *
     * @return $this
     */
    public function setSections(array $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param string[] $groups
     *
     * @return $this
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param string[] $exclude
     *
     * @return $this
     */
    public function setExclude(array $exclude)
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExcluded(): array
    {
        return $this->exclude;
    }

    /**
     * @param string[] $includeExcluded
     *
     * @return $this
     */
    public function setIncludeExcluded(array $includeExcluded)
    {
        $this->includeExcluded = $includeExcluded;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIncludeExcluded(): array
    {
        return $this->includeExcluded;
    }
}
