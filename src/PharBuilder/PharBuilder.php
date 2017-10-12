<?php

namespace PharBuilder;

use Phar;
use Seld\PharUtils\Timestamps;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class PharBuilder
{
    const PHAR_NAME = 'spryker.phar';

    /**
     * @var \PharBuilder\PharFileOptimizer
     */
    protected $pharFileOptimizer;

    /**
     * @var array
     */
    protected $directoriesToInclude = [
        '/src/Spryker',
        '/vendor/symfony/console',
        '/vendor/symfony/finder',
        '/vendor/symfony/yaml',
        '/vendor/symfony/polyfill-mbstring',
        '/vendor/symfony/process',
        '/vendor/composer',
    ];

    /**
     * @var array
     */
    protected $filesToInclude = [
        '/vendor/autoload.php',
        '/vendor/composer/autoload_namespaces.php',
        '/vendor/composer/autoload_psr4.php',
        '/vendor/composer/autoload_classmap.php',
        '/vendor/composer/autoload_files.php',
        '/vendor/composer/autoload_real.php',
        '/vendor/composer/autoload_static.php',
        '/vendor/composer/ClassLoader.php',
    ];

    /**
     * @param \PharBuilder\PharFileOptimizer $pharFileOptimizer
     */
    public function __construct(PharFileOptimizer $pharFileOptimizer)
    {
        $this->pharFileOptimizer = $pharFileOptimizer;
    }

    /**
     * @param string $pharFile
     *
     * @return void
     */
    public function build($pharFile = self::PHAR_NAME)
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $phar = new Phar($pharFile, 0, static::PHAR_NAME);
        $phar->setSignatureAlgorithm(Phar::SHA1);
        $phar->startBuffering();

        foreach ($this->getFinder() as $file) {
            $this->addFile($phar, $file);
        }

        foreach ($this->prefix($this->filesToInclude) as $filePath) {
            $this->addFile($phar, new SplFileInfo($filePath));
        }

        $this->addSprykerBin($phar);

        $this->setStub($phar);

        $phar->stopBuffering();

        unset($phar);

        $util = new Timestamps($pharFile);
        $util->updateTimestamps();
        $util->save($pharFile, Phar::SHA1);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->in($this->prefix($this->directoriesToInclude))
            ->sort($this->getSortCallback());

        return $finder;
    }

    /**
     * @param array $list
     *
     * @return array
     */
    protected function prefix(array $list)
    {
        array_walk($list, function (&$item) {
            $item = realpath(__DIR__ . '/../..' . $item);
        });

        return $list;
    }

    /**
     * @return \Closure
     */
    protected function getSortCallback()
    {
        $finderSort = function (SplFileInfo $fileA, SplFileInfo $fileB) {
            return strcmp(strtr($fileA->getRealPath(), '\\', '/'), strtr($fileB->getRealPath(), '\\', '/'));
        };

        return $finderSort;
    }

    /**
     * @param \Phar $phar
     * @param \SplFileInfo $file
     *
     * @return void
     */
    private function addFile(Phar $phar, SplFileInfo $file)
    {
        $path = $this->getRelativeFilePath($file);
        $content = $this->pharFileOptimizer->optimize(file_get_contents($file));

        $phar->addFromString($path, $content);
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return string
     */
    private function getRelativeFilePath(SplFileInfo $file)
    {
        $realPath = $file->getRealPath();
        $pathPrefix = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;

        $pos = strpos($realPath, $pathPrefix);
        $relativePath = ($pos !== false) ? substr_replace($realPath, '', $pos, strlen($pathPrefix)) : $realPath;

        return strtr($relativePath, '\\', '/');
    }

    /**
     * @param \Phar $phar
     *
     * @return void
     */
    protected function addSprykerBin(Phar $phar)
    {
        $content = file_get_contents(__DIR__ . '/../../bin/spryker');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/spryker', $content);
    }

    /**
     * @param \Phar $phar
     *
     * @return void
     */
    protected function setStub(Phar $phar)
    {
        $phar->setStub($this->getStub());
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        $stub = <<<'EOF'
#!/usr/bin/env php
<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

Phar::mapPhar('spryker.phar');
require 'phar://spryker.phar/bin/spryker';

__HALT_COMPILER();
EOF;

        return $stub;
    }
}
