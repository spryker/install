<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Communication\Style;

use Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface;
use Spryker\Zed\Install\Business\Stage\Section\SectionInterface;
use Spryker\Zed\Install\Business\Stage\StageInterface;

interface StyleInterface
{
    public const VERBOSITY_QUIET = 16;
    public const VERBOSITY_NORMAL = 32;
    public const VERBOSITY_VERBOSE = 64;
    public const VERBOSITY_VERY_VERBOSE = 128;
    public const VERBOSITY_DEBUG = 256;

    public const OUTPUT_NORMAL = 1;
    public const OUTPUT_RAW = 2;
    public const OUTPUT_PLAIN = 4;

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startInstall(StageInterface $stage);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endInstall(StageInterface $stage);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param string|null $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, $store = null);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param string|null $store
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, $exitCode, $store = null);

    /**
     * @param \Spryker\Zed\Install\Business\Stage\Section\Command\CommandInterface $command
     *
     * @return void
     */
    public function dryRunCommand(CommandInterface $command);

    /**
     * @param string $output
     *
     * @return void
     */
    public function innerCommand($output);

    /**
     * @param string $output
     *
     * @return void
     */
    public function note($output);

    /**
     * @param int $count
     *
     * @return void
     */
    public function newLine($count = 1);

    /**
     * @param string|array $messages
     * @param int $options
     *
     * @return void
     */
    public function write($messages, $options = 0);

    /**
     * @param string $question
     * @param bool $default
     *
     * @return bool
     */
    public function confirm($question, $default);

    /**
     * @param string $question
     * @param array $choices
     * @param string|int|null $default
     *
     * @return bool|mixed|string|null
     */
    public function choice($question, array $choices, $default = null);

    /**
     * @return void
     */
    public function flushBuffer();
}
