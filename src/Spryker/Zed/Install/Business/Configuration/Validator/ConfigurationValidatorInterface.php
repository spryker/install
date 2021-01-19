<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Validator;

interface ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     *
     * @throws \Spryker\Zed\Install\Business\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    public function validate(array $configuration): void;
}
