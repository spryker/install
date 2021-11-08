<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Stage\Section;

use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;

interface SectionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return $this
     */
    public function markAsExcluded();

    /**
     * @return bool
     */
    public function isExcluded(): bool;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     *
     * @return $this
     */
    public function addCommand(CommandInterface $command);

    /**
     * @return array<\Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface>
     */
    public function getCommands(): array;

    /**
     * @param string $commandName
     *
     * @return \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface
     */
    public function getCommand(string $commandName): CommandInterface;

    /**
     * @param string $preCommand
     *
     * @return $this
     */
    public function setPreCommand(string $preCommand);

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
    public function setPostCommand(string $postCommand);

    /**
     * @return bool
     */
    public function hasPostCommand(): bool;

    /**
     * @return string
     */
    public function getPostCommand(): string;
}
