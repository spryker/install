<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Validator;

use Spryker\Deploy\Configuration\Exception\ConfigurationException;

class ConfigurationValidator implements ConfigurationValidatorInterface
{
    const SECTIONS = 'sections';

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
     * @throws \Spryker\Deploy\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateSections(array $configuration)
    {
        if (!isset($configuration[static::SECTIONS])) {
            throw new ConfigurationException('No sections defined your configuration.');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Deploy\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateCommands(array $configuration)
    {
        foreach ($configuration[static::SECTIONS] as $sectionName => $commands) {
            if (count($commands) === 0) {
                throw new ConfigurationException(sprintf('No commands defined in section "%s".', $sectionName));
            }
        }
    }
}
