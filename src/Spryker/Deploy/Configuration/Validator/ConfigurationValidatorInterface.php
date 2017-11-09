<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Validator;

interface ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     *
     * @throws \Spryker\Deploy\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    public function validate(array $configuration);
}
