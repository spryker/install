<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace PharBuilder;

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

class PharFileOptimizer
{
    /**
     * @param string $fileContent
     *
     * @return string
     */
    public function optimize($fileContent)
    {
        if (!function_exists('token_get_all')) {
            return $fileContent;
        }

        $output = '';
        foreach (token_get_all($fileContent) as $token) {
            $output .= $this->getTokenContent($token);
        }

        return $output;
    }

    /**
     * @param string|array $token
     *
     * @return string
     */
    protected function getTokenContent($token)
    {
        if (is_string($token)) {
            return $token;
        }

        if (in_array($token[0], [T_COMMENT, T_DOC_COMMENT])) {
            return str_repeat("\n", substr_count($token[1], "\n"));
        }

        if (T_WHITESPACE === $token[0]) {
            $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
            $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
            $whitespace = preg_replace('{\n +}', "\n", $whitespace);

            return $whitespace;
        }

        return $token[1];
    }
}
