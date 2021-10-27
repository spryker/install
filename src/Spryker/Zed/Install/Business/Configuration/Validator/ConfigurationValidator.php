<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Validator;

use Spryker\Zed\Install\Business\Configuration\Exception\ConfigurationException;

class ConfigurationValidator implements ConfigurationValidatorInterface
{
    /**
     * @var string
     */
    public const SECTIONS = 'sections';

    /**
     * @param array $configuration
     *
     * @return void
     */
    public function validate(array $configuration): void
    {
        $this->validateSections($configuration);
        $this->validateCommands($configuration);
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Zed\Install\Business\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateSections(array $configuration): void
    {
        if (!isset($configuration[static::SECTIONS])) {
            throw new ConfigurationException('No sections defined in your configuration.');
        }
    }

    /**
     * @param array $configuration
     *
     * @throws \Spryker\Zed\Install\Business\Configuration\Exception\ConfigurationException
     *
     * @return void
     */
    protected function validateCommands(array $configuration): void
    {
        foreach ($configuration[static::SECTIONS] as $sectionName => $commands) {
            if ($commands === null || count($commands) === 0) {
                throw new ConfigurationException(sprintf('No commands defined in section "%s".', $sectionName));
            }
        }
    }
}
