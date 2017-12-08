<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Validator;

interface ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     *
     * @throws \Spryker\Install\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    public function validate(array $configuration);
}
