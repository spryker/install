<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Install\Business\Configuration\Loader;

use Spryker\Zed\Install\Business\Configuration\Loader\Exception\ConfigurationFileNotFoundException;
use Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidatorInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var \Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidatorInterface
     */
    protected $configurationValidator;

    /**
     * @param \Spryker\Zed\Install\Business\Configuration\Validator\ConfigurationValidatorInterface $configurationValidator
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

        $configuration = (array)Yaml::parse((string)file_get_contents($pathToRecipe));
        $this->configurationValidator->validate($configuration);

        return $configuration;
    }

    /**
     * @param string $recipe
     *
     * @throws \Spryker\Zed\Install\Business\Configuration\Loader\Exception\ConfigurationFileNotFoundException
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
            sprintf('%s/config/install/%s.yml', SPRYKER_ROOT, $recipe),
            sprintf('%s/config/install/%s', SPRYKER_ROOT, $recipe),
            sprintf('%s/%s', SPRYKER_ROOT, $recipe),
            $recipe,
        ];

        return $recipePaths;
    }
}
