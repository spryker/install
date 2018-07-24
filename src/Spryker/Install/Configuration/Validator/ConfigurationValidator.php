<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Install\Configuration\Validator;

use Spryker\Install\Configuration\Exception\ConfigurationException;

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
     * @throws \Spryker\Install\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateSections(array $configuration)
    {
        if (!isset($configuration[static::SECTIONS])) {
            throw new ConfigurationException('No sections defined in your configuration.');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Install\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateCommands(array $configuration)
    {
        foreach ($configuration[static::SECTIONS] as $sectionName => $commands) {
            if ($commands === null || count($commands) === 0) {
                throw new ConfigurationException(sprintf('No commands defined in section "%s".', $sectionName));
            }
        }
    }
}
