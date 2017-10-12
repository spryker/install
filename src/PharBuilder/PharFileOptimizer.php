<?php

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
            if (is_string($token)) {
                $output .= $token;
                continue;
            }

            if (in_array($token[0], [T_COMMENT, T_DOC_COMMENT])) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
                continue;
            }
            if (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
                continue;
            }

            $output .= $token[1];
        }

        return $output;
    }
}
