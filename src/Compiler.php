<?php

namespace Crowles\App;

use Exception;

/**
 * PHP version Compiler.
 *
 * This class is responsible for extracting bzip2 archives obtained
 * via Crowles\App\Downloader::download() and compiling them for
 * deployment to chroot jails.
 *
 * @package Crowles\App
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Compiler extends Base
{
    /** @var $options array */
    protected $options;

    /** @var $deployPath string */
    protected $deployPath;

    /**
     * Compiler constructor.
     *
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     * PHP version compiler.
     *
     * @param $version
     * @param $target
     *
     * @return string
     *
     * @throws Exception
     */
    public function compile($version, $target) : string
    {
        $this->setDeployPath($version);

        mkdir($this->deployPath);
        shell_exec("tar -xvBf {$target} -C {$this->deployPath} 2>&1");
        chdir($this->deployPath);

        while (!file_exists("{$this->deployPath}/php-{$version}/configure")) {
            foreach (glob("$this->deployPath/*", GLOB_ONLYDIR) as $out) {
                if (!in_array($out, array('.', '..'))) {
                    continue 2;
                }
            }
            throw new Exception('Failed to find php build folder');
        }

        chdir($this->deployPath . "/php-{$version}");

        $cmd = "  ./configure \\\n    --" . implode(" \\\n    --", $this->getOptions()) . "\n  2>&1";
        if ($this->isDebug()) {
            echo "Configuring with: \n{$cmd}\n...";
        }
        $config = shell_exec($cmd);

        if (strpos($config, 'creating main/php_config.h') === false) {
            throw new Exception("Failed to configure PHP\n\nLine:\n{$cmd}\n\nLog:\n{$config}");
        }

        if ($this->isDebug()) {
            echo "\nCompiling\n...";
        }
        $build = shell_exec('make install 2>&1');

        if (strpos($build, 'Installing PHP CGI binary') === false) {
            throw new Exception("Failed to build PHP\n\nLog:\n{$build}");
        }

        if ($this->isDebug()) {
            echo "\nPHP successfully compiled to $this->deployPath\n...";
        }

        chdir(dirname(__FILE__));

        return $config . "\n" . $build;
    }

    /**
     * Returns the options for the make configure script.
     *
     * @return mixed
     */
    public function getOptions()
    {
        $deployPath = $this->getDeployPath();
        if (is_null($this->options)) {
            // Set default
           $this->setOptions([
               "with-config-file-path=$deployPath/etc",
               "prefix=$deployPath",
               "with-layout=GNU",
               "enable-mbstring",
               "enable-calendar",
               "enable-bcmath",
               "enable-pdo",
               "enable-sockets",
               "enable-soap",
               "with-curl",
               "with-gd",
               "with-jpeg-dir",
               "with-png-dir",
               "with-zlib",
               "with-tidy",
               "with-mysqli",
               "with-pdo-mysql",
               "with-pdo-sqlite",
               "with-gettext",
               "with-openssl"
           ]);
        }

        return $this->options;
    }

    /**
     * Sets the options for the make configure script.
     *
     * @param mixed $options
     *
     * @return Compiler
     */
    public function setOptions($options): Compiler
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Returns the instance deploy path.
     *
     * @return mixed
     */
    public function getDeployPath()
    {
        return $this->deployPath;
    }

    /**
     * Sets the instance deploy path.
     *
     * @param mixed $deployPath
     *
     * @return Compiler
     */
    public function setDeployPath($deployPath): Compiler
    {
        $this->deployPath = "/tmp/php-$deployPath";

        return $this;
    }

    /**
     * Remove utility.
     *
     * @param $dir
     *
     * @return void
     */
    protected function remove($dir) : void
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }

            $file = "$dir/$file";

            if (is_dir($file))
                $this->remove($file);
            else
                unlink($file);
        }

        if (is_dir($dir)) {
            rmdir($dir);
        }
    }
}