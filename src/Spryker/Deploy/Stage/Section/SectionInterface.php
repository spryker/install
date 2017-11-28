<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Stage\Section;

use Spryker\Deploy\Stage\Section\Command\CommandInterface;

interface SectionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return $this
     */
    public function markAsExcluded(): self;

    /**
     * @return bool
     */
    public function isExcluded(): bool;

    /**
     * @param \Spryker\Deploy\Stage\Section\Command\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command): self;

    /**
     * @return \Spryker\Deploy\Stage\Section\Command\CommandInterface[]
     */
    public function getCommands(): array;

    /**
     * @param string $commandName
     *
     * @return \Spryker\Deploy\Stage\Section\Command\CommandInterface
     */
    public function getCommand(string $commandName): CommandInterface;

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand(string $preCommand): self;

    /**
     * @return bool
     */
    public function hasPreCommand(): bool;

    /**
     * @return string
     */
    public function getPreCommand(): string;

    /**
     * @param string $postCommand
     *
     * @return $this
     */
    public function setPostCommand(string $postCommand): self;

    /**
     * @return bool
     */
    public function hasPostCommand(): bool;

    /**
     * @return string
     */
    public function getPostCommand(): string;
}
