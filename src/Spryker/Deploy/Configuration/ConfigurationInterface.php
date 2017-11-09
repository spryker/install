<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration;

use Spryker\Deploy\Stage\Section\Command\CommandInterface;
use Spryker\Deploy\Stage\StageInterface;
use Spryker\Style\StyleInterface;

interface ConfigurationInterface
{
    /**
     * @param \Spryker\Style\StyleInterface $output
     *
     * @return $this
     */
    public function setOutput(StyleInterface $output): self;

    /**
     * @return \Spryker\Style\StyleInterface
     */
    public function getOutput(): StyleInterface;

    /**
     * @param \Spryker\Deploy\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage): self;

    /**
     * @return \Spryker\Deploy\Stage\StageInterface
     */
    public function getStage(): StageInterface;

    /**
     * @param bool $isDebugMode
     *
     * @return $this
     */
    public function setIsDebugMode(bool $isDebugMode): self;

    /**
     * @return bool
     */
    public function isDebugMode(): bool;

    /**
     * @param bool $isDryRun
     *
     * @return $this
     */
    public function setIsDryRun(bool $isDryRun): self;

    /**
     * @return bool
     */
    public function isDryRun(): bool;

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env): self;

    /**
     * @return array
     */
    public function getEnv(): array;

    /**
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores): self;

    /**
     * @return array
     */
    public function getStores(): array;

    /**
     * @param array $executableStores
     *
     * @return $this
     */
    public function setExecutableStores(array $executableStores): self;

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
    public function setAskBeforeContinueAfterException(bool $shouldAskBeforeContinueAfterException): self;

    /**
     * @param string $name
     *
     * @return \Spryker\Deploy\Stage\Section\Command\CommandInterface
     */
    public function findCommand(string $name): CommandInterface;
}
