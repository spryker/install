<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration;

use Spryker\Setup\Stage\StageInterface;
use Symfony\Component\Console\Style\StyleInterface;

interface ConfigurationInterface
{
    /**
     * @param \Symfony\Component\Console\Style\StyleInterface $output
     *
     * @return $this
     */
    public function setOutput(StyleInterface $output);

    /**
     * @return \Symfony\Component\Console\Style\StyleInterface
     */
    public function getOutput();

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return $this
     */
    public function setStage(StageInterface $stage);

    /**
     * @return \Spryker\Setup\Stage\StageInterface
     */
    public function getStage();

    /**
     * @param bool $isDebugMode
     *
     * @return $this
     */
    public function setIsDebugMode($isDebugMode);

    /**
     * @return bool
     */
    public function isDebugMode();

    /**
     * @param bool $isDryRun
     *
     * @return $this
     */
    public function setIsDryRun($isDryRun);

    /**
     * @return bool
     */
    public function isDryRun();

    /**
     * @param array $env
     *
     * @return $this
     */
    public function setEnv(array $env);

    /**
     * @return array
     */
    public function getEnv();

    /**
     * @param array $stores
     *
     * @return $this
     */
    public function setStores(array $stores);

    /**
     * @return array
     */
    public function getStores();

    /**
     * @param array $executableStores
     *
     * @return $this
     */
    public function setExecutableStores(array $executableStores);

    /**
     * @return array
     */
    public function getExecutableStores();

    /**
     * @return bool
     */
    public function shouldAskBeforeContinueAfterException();

    /**
     * @param bool $shouldAskBeforeContinueAfterException
     *
     * @return $this
     */
    public function setAskBeforeContinueAfterException($shouldAskBeforeContinueAfterException);

    /**
     * @param string $name
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function findCommand($name);
}
