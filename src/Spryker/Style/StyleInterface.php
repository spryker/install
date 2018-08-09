<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Style;

use Spryker\Install\Stage\Section\Command\CommandInterface;
use Spryker\Install\Stage\Section\SectionInterface;
use Spryker\Install\Stage\StageInterface;

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
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return void
     */
    public function startInstall(StageInterface $stage);

    /**
     * @param \Spryker\Install\Stage\StageInterface $stage
     *
     * @return void
     */
    public function endInstall(StageInterface $stage);

    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function startSection(SectionInterface $section);

    /**
     * @param \Spryker\Install\Stage\Section\SectionInterface $section
     *
     * @return void
     */
    public function endSection(SectionInterface $section);

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param null|string $store
     *
     * @return void
     */
    public function startCommand(CommandInterface $command, $store = null);

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
     * @param int $exitCode
     * @param null|string $store
     *
     * @return void
     */
    public function endCommand(CommandInterface $command, $exitCode, $store = null);

    /**
     * @param \Spryker\Install\Stage\Section\Command\CommandInterface $command
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
     * @return bool|mixed|null|string
     */
    public function choice($question, array $choices, $default = null);

    /**
     * @return void
     */
    public function flushBuffer();
}
