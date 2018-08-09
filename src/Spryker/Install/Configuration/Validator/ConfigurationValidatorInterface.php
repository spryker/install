<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
