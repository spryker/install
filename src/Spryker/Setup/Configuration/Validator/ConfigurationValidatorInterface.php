<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Validator;

interface ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     *
     * @throws \Spryker\Setup\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    public function validate(array $configuration);
}
