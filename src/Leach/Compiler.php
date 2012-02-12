<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leach;

use Symfony\Component\Finder\Finder;

/**
 * @codeCoverageIgnore
 */
class Compiler
{
    /**
     * @param string $pharFile (optional)
     *
     * @return void
     */
    public function compile($pharFile = 'leach.phar')
    {
        $this->rootDir = realpath(__DIR__.'/../..');

        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $phar = new \Phar($pharFile, 0, 'leach.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        $finder = new Finder();
        $finder
            ->files()
            ->name('*.php')
            ->notName('Compiler.php')
            ->in($this->rootDir.'/src')
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $finder = new Finder();
        $finder
            ->files()
            ->name('*.php')
            ->in(array(
                $this->rootDir.'/vendor/phuedx/tnetstring',
                $this->rootDir.'/vendor/symfony/console',
                $this->rootDir.'/vendor/symfony/http-foundation',
                $this->rootDir.'/vendor/symfony/http-kernel',
            ))
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo($this->rootDir.'/vendor/.composer/ClassLoader.php'));
        $this->addFile($phar, new \SplFileInfo($this->rootDir.'/vendor/.composer/autoload.php'));
        $this->addFile($phar, new \SplFileInfo($this->rootDir.'/vendor/.composer/autoload_namespaces.php'));
        $this->addLeachBin($phar);

        $phar->setStub($this->getStub());

        $phar->stopBuffering();

        $phar->compressFiles(\Phar::GZ);

        // $this->addFile($phar, new \SplFileInfo($this->rootDir.'/LICENSE'), false);

        unset($phar);
    }

    /**
     * @param \Phar $phar
     * @param \SplFileInfo $file
     * @param Boolean $strip (optional);
     *
     * @return void
     */
    private function addFile($phar, \SplFileInfo $file, $strip = true)
    {
        $path = str_replace($this->rootDir.DIRECTORY_SEPARATOR, '', $file->getRealPath());

        if ($strip) {
            $content = php_strip_whitespace($file);
        } else {
            $content = "\n".file_get_contents($file)."\n";
        }

        $phar->addFromString($path, $content);
    }

    /**
     * @param \Phar $phar
     *
     * @return void
     */
    private function addLeachBin($phar)
    {
        $content = file_get_contents(__DIR__.'/../../bin/leach');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/leach', $content);
    }

    /**
     * @return string
     */
    private function getStub()
    {
        return <<<'BIN'
#!/usr/bin/env php
<?php

/*
 * This file is part of the Leach package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Phar::mapPhar('leach.phar');

require 'phar://leach.phar/bin/leach';

__HALT_COMPILER();
BIN;
    }
}
