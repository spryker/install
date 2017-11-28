<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Deploy\Configuration\Loader;

use Spryker\Deploy\Configuration\Loader\Exception\ConfigurationFileNotFoundException;
use Spryker\Deploy\Configuration\Validator\ConfigurationValidatorInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var \Spryker\Deploy\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected $configurationValidator;

    /**
     * @param \Spryker\Deploy\Configuration\Validator\ConfigurationValidatorInterface $configurationValidator
     */
    public function __construct(ConfigurationValidatorInterface $configurationValidator)
    {
        $this->configurationValidator = $configurationValidator;
    }

    /**
     * @param string $recipe
     *
     * @return array
     */
    public function loadConfiguration(string $recipe): array
    {
        $pathToRecipe = $this->getPathToRecipe($recipe);

        $configuration = (array)Yaml::parse(file_get_contents($pathToRecipe));
        $this->configurationValidator->validate($configuration);

        return $configuration;
    }

    /**
     * @param string $recipe
     *
     * @throws \Spryker\Deploy\Configuration\Loader\Exception\ConfigurationFileNotFoundException
     *
     * @return string
     */
    protected function getPathToRecipe(string $recipe): string
    {
        $recipePaths = $this->buildRecipePaths($recipe);

        foreach ($recipePaths as $recipePath) {
            if (file_exists($recipePath)) {
                return $recipePath;
            }
        }

        throw new ConfigurationFileNotFoundException(
            sprintf(
                'Could not resolve path for your recipe. Check %s.',
                implode(', ', $recipePaths)
            )
        );
    }

    /**
     * @param string $recipe
     *
     * @return array
     */
    protected function buildRecipePaths(string $recipe): array
    {
        $recipePaths = [
            sprintf('%s/config/deploy/%s.yml', SPRYKER_ROOT, $recipe),
            sprintf('%s/config/deploy/%s', SPRYKER_ROOT, $recipe),
            sprintf('%s/%s', SPRYKER_ROOT, $recipe),
            $recipe,
        ];

        return $recipePaths;
    }
}
