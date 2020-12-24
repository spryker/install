<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration;

use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Stage\StageInterface;
use Spryker\Zed\Install\Business\Style\StyleInterface;

interface ConfigurationInterface
{
    /**
     * @param \Spryker\Zed\Install\Business\Style\StyleInterface $output
     *
     * @return $this
     */
    public function setOutput(StyleInterface $output);

    /**
     * @return \Spryker\Zed\Install\Business\Style\StyleInterface
     */
    public function getOutput(): StyleInterface;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage);

    /**
     * @return \Spryker\Zed\Install\Business\Stage\StageInterface
     */
    public function getStage(): StageInterface;

    /**
     * @param bool $isDebugMode
     *
     * @return $this
     */
    public function setIsDebugMode(bool $isDebugMode);

    /**
     * @return bool
     */
    public function isDebugMode(): bool;

    /**
     * @param bool $isDryRun
     *
     * @return $this
     */
    public function setIsDryRun(bool $isDryRun);

    /**
     * @return bool
     */
    public function isDryRun(): bool;

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @return array
     */
    public function getEnv(): array;

    /**
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores);

    /**
     * @return array
     */
    public function getStores(): array;

    /**
     * @param int $commandTimeout
     *
     * @return $this
     */
    public function setCommandTimeout(int $commandTimeout);

    /**
     * @return int|null
     */
    public function getCommandTimeout(): ?int;

    /**
     * @param array $executableStores
     *
     * @return $this
     */
    public function setExecutableStores(array $executableStores);

    /**
     * @return array
     */
    public function getExecutableStores(): array;

    /**
     * @return bool
     */
    public function shouldAskBeforeContinueAfterException(): bool;

    /**
     * @param bool $shouldAskBeforeContinueAfterException
     *
     * @return $this
     */
    public function setAskBeforeContinueAfterException(bool $shouldAskBeforeContinueAfterException);

    /**
     * @param string $name
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface;

    /**
     * @param string[] $sections
     *
     * @return $this
     */
    public function setSections(array $sections);

    /**
     * @return string[]
     */
    public function getSections(): array;

    /**
     * @param string[] $groups
     *
     * @return $this
     */
    public function setGroups(array $groups);

    /**
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @param string[] $exclude
     *
     * @return $this
     */
    public function setExclude(array $exclude);

    /**
     * @return string[]
     */
    public function getExcluded(): array;

    /**
     * @param string[] $includeExcluded
     *
     * @return $this
     */
    public function setIncludeExcluded(array $includeExcluded);

    /**
     * @return string[]
     */
    public function getIncludeExcluded(): array;
}
