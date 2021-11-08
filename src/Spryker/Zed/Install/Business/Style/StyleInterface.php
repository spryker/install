<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Style;

use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;
use Spryker\Zed\Install\Business\Stage\StageInterface;

interface StyleInterface
{
    /**
     * @var int
     */
    public const VERBOSITY_QUIET = 16;

    /**
     * @var int
     */
    public const VERBOSITY_NORMAL = 32;

    /**
     * @var int
     */
    public const VERBOSITY_VERBOSE = 64;

    /**
     * @var int
     */
    public const VERBOSITY_VERY_VERBOSE = 128;

    /**
     * @var int
     */
    public const VERBOSITY_DEBUG = 256;

    /**
     * @var int
     */
    public const OUTPUT_NORMAL = 1;

    /**
     * @var int
     */
    public const OUTPUT_RAW = 2;

    /**
     * @var int
     */
    public const OUTPUT_PLAIN = 4;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startInstall(StageInterface $stage): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endInstall(StageInterface $stage): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, ?string $store = null): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param string|null $store
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, int $exitCode, ?string $store = null): void;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    public function dryRunCommand(CommandInterface $command): void;

    /**
     * @param string $output
     *
     * @return void
     */
    public function innerCommand(string $output): void;

    /**
     * @param string $output
     *
     * @return void
     */
    public function note(string $output): void;

    /**
     * @param int $count
     *
     * @return void
     */
    public function newLine(int $count = 1): void;

    /**
     * @param array|string $messages
     * @param int $options
     *
     * @return void
     */
    public function write($messages, int $options = 0): void;

    /**
     * @param string $question
     * @param bool $default
     *
     * @return bool
     */
    public function confirm(string $question, bool $default): bool;

    /**
     * @param string $question
     * @param array $choices
     * @param string|int|null $default
     *
     * @return mixed|string|bool|null
     */
    public function choice(string $question, array $choices, $default = null);

    /**
     * @return void
     */
    public function flushBuffer(): void;
}
