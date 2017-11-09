<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Setup\Configuration\Loader;

use Spryker\Setup\Configuration\Loader\Exception\ConfigurationFileNotFoundException;
use Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var \Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected $configurationValidator;

    /**
     * @param \Spryker\Setup\Configuration\Validator\ConfigurationValidatorInterface $configurationValidator
     */
    public function __construct(ConfigurationValidatorInterface $configurationValidator)
    {
        $this->configurationValidator = $configurationValidator;
    }

    /**
     * @param string $stageName
     *
     * @throws \Spryker\Setup\Configuration\Loader\Exception\ConfigurationFileNotFoundException
     *
     * @return array
     */
    public function loadConfiguration(string $stageName): array
    {
        $configFile = SPRYKER_ROOT . '/.spryker/setup/' . $stageName . '.yml';
        if (!file_exists($configFile)) {
            throw new ConfigurationFileNotFoundException(
                sprintf(
                    'File "%s" does not exists. Please add the expected file.',
                    $configFile
                )
            );
        }

        $configuration = (array)Yaml::parse(file_get_contents($configFile));
        $this->configurationValidator->validate($configuration);

        return $configuration;
    }
}
