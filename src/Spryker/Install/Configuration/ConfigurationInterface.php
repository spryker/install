<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Install\Configuration;

use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Install\Stage\StageInterface;
use Spryker\Style\StyleInterface;

interface ConfigurationInterface
{
    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return $this
     */
    public function setOutput(StyleInterface $output);

    /**
     * @return \Spryker\Style\StyleInterface
     */
    public function getOutput(): StyleInterface;

    /**
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage);

    /**
     * @return \Spryker\Install\Stage\StageInterface
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
     * @return \Spryker\Install\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface;
}
