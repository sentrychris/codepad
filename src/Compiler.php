<?php

namespace Crowles\Cophi;

use Exception;

/**
 * PHP Compiler.
 *
 * This class is responsible for extracting bzip2 archives obtained
 * via Crowles\Cophi\Downloader::download() and compiling them for
 * deployment to chroot jails.
 *
 * @package Crowles\Cophi
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Compiler extends Base
{
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
     *
     * Version compiler.
     *
     * @param $options
     * @param $archive
     * @param $version
     * @return string
     * @throws Exception
     */
    public function compile($options, $archive, $version) : string
    {
        $out = "/tmp/php-{$version}/";
        $in = $archive;

        mkdir($out);
        shell_exec("tar -xvBf {$in} -C {$out} 2>&1");
        chdir($out);

        while (!file_exists("{$out}/php-{$version}/configure")) {
            foreach (glob("$out/*", GLOB_ONLYDIR) as $out) {
                if (!in_array($out, array('.', '..'))) {
                    continue 2;
                }
            }
            throw new Exception('Failed to find php build folder');
        }

        chdir($out . "/php-{$version}");

        $cmd = "  ./configure \\\n    --" . implode(" \\\n    --", $options) . "\n  2>&1";
        if ($this->isDebug()) {
            echo "Configuring with: \n{$cmd}\n...";
        }
        $config_log = shell_exec($cmd);

        if (strpos($config_log, 'creating main/php_config.h') === false) {
            throw new Exception("Failed to configure PHP\n\nLine:\n{$cmd}\n\nLog:\n{$config_log}");
        }

        if ($this->isDebug()) {
            echo "\nCompiling\n...";
        }
        $build_log = shell_exec('make install 2>&1');

        if (strpos($build_log, 'Installing PHP CGI binary') === false) {
            throw new Exception("Failed to build PHP\n\nLog:\n{$build_log}");
        }

        chdir(dirname(__FILE__));

        return $config_log . "\n" . $build_log;
    }

    /**
     * Remove utility.
     *
     * @param $dir
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