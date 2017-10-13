<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Filter;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class InteractiveCommandFilter implements FilterInterface
{
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $output;

    /**
     * @param \Symfony\Component\Console\Style\SymfonyStyle $output
     */
    public function __construct(SymfonyStyle $output)
    {
        $this->output = $output;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function filter(array $items)
    {
        $filtered = [];

        foreach ($items as $commandName => $commandDefinition) {
            $question = new ConfirmationQuestion(sprintf('Should command "%s" be executed?', $commandName));

            if ($this->output->ask($question) === true) {
                $filtered[$commandName] = $commandDefinition;
            }
        }

        return $filtered;
    }
}
