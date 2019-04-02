<?php
/**
 * Cophi PHP Compiler test.
 *
 * @author Chris Rowles
 * @package Cophi
 */

use Crowles\Cophi\Compiler;

require __DIR__ . '/../config/bootstrap.php';

init(new Compiler(), "7.3.3", true);

/**
 *
 * init test method.
 *
 * @param Compiler $compiler
 * @param $version
 * @param bool $debug
 */
function init(Compiler $compiler, $version, $debug = false)
{
    $compiler->setDebug($debug);

    $php = [
        'archive' => "/tmp/php-{$version}.tar.bz2",
        'version' => $version
    ];

    $deploy_path = "/tmp/php-{$php['version']}";
    $options = [
        "with-config-file-path={$deploy_path}/etc",
        "prefix={$deploy_path}",
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
    ];

    try {
        $build_log = $compiler->compile($options, $php['archive'], $php['version']);

        if ($debug) {
            echo "Installed {$php['version']} to {$deploy_path}\n";
        }

        if ($debug) {
            echo "Build Complete\n";
        }

        echo "SUCCESS: [OK] {$build_log}";
    } catch (\Exception $e) {
        echo "ERROR: [COMPILE] {$e->getMessage()}";
    }
}