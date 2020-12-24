<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Style;

use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

trait InputHelper
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @param string $question
     * @param bool $default
     *
     * @return bool
     */
    public function confirm($question, $default): bool
    {
        return (bool)$this->askQuestion(new ConfirmationQuestion($question, $default));
    }

    /**
     * @param string $question
     * @param array $choices
     * @param string|int|null $default
     *
     * @return bool|mixed|string|null
     */
    public function choice($question, array $choices, $default = null)
    {
        if ($default) {
            $values = array_flip($choices);
            $default = $values[$default];
        }

        $question = new ChoiceQuestion($question, $choices, $default);
        $question->setMultiselect(true);

        return $this->askQuestion($question);
    }

    /**
     * @param \Symfony\Component\Console\Question\Question $question
     *
     * @return mixed
     */
    protected function askQuestion(Question $question)
    {
        $questionHelper = new SymfonyQuestionHelper();

        return $questionHelper->ask($this->input, $this->output, $question);
    }
}
