<?php

class PharBuilder
{

    const PHAR_NAME = 'spryker.phar';

    public function build()
    {
        $phar = new \Phar(static::PHAR_NAME, 0, static::PHAR_NAME);
        $phar->setSignatureAlgorithm(Phar::SHA1);
        $phar->startBuffering();

        $finderSort = function (SplFileInfo $fileA, SplFileInfo $fileB) {
            return strcmp(strtr($fileA->getRealPath(), '\\', '/'), strtr($fileB->getRealPath(), '\\', '/'));
        };
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->notName('Compiler.php')
            ->notName('ClassLoader.php')
            ->in(__DIR__.'/..')
            ->sort($finderSort)
        ;
        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/Autoload/ClassLoader.php'), false);
        $finder = new Finder();
        $finder->files()
            ->name('*.json')
            ->in(__DIR__.'/../../res')
            ->in(SpdxLicenses::getResourcesDir())
            ->sort($finderSort)
        ;
        foreach ($finder as $file) {
            $this->addFile($phar, $file, false);
        }
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/seld/cli-prompt/res/hiddeninput.exe'), false);
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->name('LICENSE')
            ->exclude('Tests')
            ->exclude('tests')
            ->exclude('docs')
            ->in(__DIR__.'/../../vendor/symfony/')
            ->in(__DIR__.'/../../vendor/seld/jsonlint/')
            ->in(__DIR__.'/../../vendor/seld/cli-prompt/')
            ->in(__DIR__.'/../../vendor/justinrainbow/json-schema/')
            ->in(__DIR__.'/../../vendor/composer/spdx-licenses/')
            ->in(__DIR__.'/../../vendor/composer/semver/')
            ->in(__DIR__.'/../../vendor/composer/ca-bundle/')
            ->in(__DIR__.'/../../vendor/psr/')
            ->sort($finderSort)
        ;
        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/autoload.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_namespaces.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_psr4.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_classmap.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_files.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_real.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/autoload_static.php'));
        if (file_exists(__DIR__.'/../../vendor/composer/include_paths.php')) {
            $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/include_paths.php'));
        }
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../vendor/composer/ClassLoader.php'));
        $this->addFile($phar, new \SplFileInfo(CaBundle::getBundledCaBundlePath()), false);
        $this->addComposerBin($phar);
        // Stubs
        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../../LICENSE'), false);
        unset($phar);
        // re-sign the phar with reproducible timestamp / signature
        $util = new Timestamps($pharFile);
        $util->updateTimestamps($this->versionDate);
        $util->save($pharFile, \Phar::SHA1);
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return '';
    }

}

$pharBuilder = new PharBuilder();
$pharBuilder->build();
