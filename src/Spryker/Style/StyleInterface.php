<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Style;

use Spryker\Setup\Stage\Section\Command\CommandInterface;
use Spryker\Setup\Stage\Section\SectionInterface;
use Spryker\Setup\Stage\StageInterface;

interface StyleInterface
{
    const VERBOSITY_QUIET = 16;
    const VERBOSITY_NORMAL = 32;
    const VERBOSITY_VERBOSE = 64;
    const VERBOSITY_VERY_VERBOSE = 128;
    const VERBOSITY_DEBUG = 256;

    const OUTPUT_NORMAL = 1;
    const OUTPUT_RAW = 2;
    const OUTPUT_PLAIN = 4;

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startSetup(StageInterface $stage);

    /**
     * @param \Spryker\Setup\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endSetup(StageInterface $stage);

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section);

    /**
     * @param \Spryker\Setup\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section);

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param null|string $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, $store = null);

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     *
     * @return mixed
     */
    public function endCommand(CommandInterface $command, $exitCode);

    /**
     * @param \Spryker\Setup\Stage\Section\Command\CommandInterface $command
     *
     * @return mixed
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
     * Add newline(s).
     *
     * @param int $count The number of newlines
     *
     * @return void
     */
    public function newLine($count = 1);

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param int $options A bitmask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
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
    public function confirm($question, $default = true);

    /**
     * @param string $question
     * @param array $choices
     * @param string|int|null $default
     *
     * @return bool|mixed|null|string
     */
    public function choice($question, array $choices, $default = null);
}
