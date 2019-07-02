<?php

use Versyx\Jail\Downloader;
use Versyx\Jail\Compiler;

require __DIR__ . '/../config/bootstrap.php';

$debug = true;
$version = "7.0.33";
init(new Downloader($debug), new Compiler($debug), $version);

/**
 *
 * init test method.
 *
 * @param Downloader $downloader
 * @param Compiler $compiler
 * @param string $version
 */
function init(Downloader $downloader, Compiler $compiler, string $version)
{
    try {
        $php = $downloader->setVersion($version)->download();
        // E.G. $compiler->compile("7.3.3", "/tmp/phpvrh0Jt");
        $compiler->compile($php->getVersion(), $php->getTarget());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
