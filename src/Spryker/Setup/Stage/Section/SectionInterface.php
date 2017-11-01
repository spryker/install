<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Stage\Section;

use Spryker\Setup\Stage\Section\Command\CommandInterface;

interface SectionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return $this
     */
    public function markAsExcluded();

    /**
     * @return bool
     */
    public function isExcluded();

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command);

    /**
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface[]
     */
    public function getCommands();

    /**
     * @param string $commandName
     *
     * @return \Spryker\Setup\Stage\Section\Command\CommandInterface
     */
    public function getCommand($commandName);

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand($preCommand);

    /**
     * @return bool
     */
    public function hasPreCommand();

    /**
     * @return string
     */
    public function getPreCommand();

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand($postCommand);

    /**
     * @return bool
     */
    public function hasPostCommand();

    /**
     * @return string
     */
    public function getPostCommand();
}
