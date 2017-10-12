<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Configuration\Validator;

use Spryker\Exception\ConfigurationException;

class ConfigurationValidator implements ConfigurationValidatorInterface
{
    /**
     * @param array $configuration
     *
     * @return void
     */
    public function validate(array $configuration)
    {
        $this->validateSections($configuration);
        $this->validateCommands($configuration);
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateSections(array $configuration)
    {
        if (count($configuration['sections']) === 0) {
            throw new ConfigurationException('No sections defined your configuration.');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateCommands(array $configuration)
    {
        foreach ($configuration['sections'] as $sectionName => $commands) {
            if (count($commands) === 0) {
                throw new ConfigurationException(sprintf('No commands defined in section "%s".', $sectionName));
            }
        }
    }
}
