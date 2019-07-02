[WIP]

This is a tool for creating jailed PHP instances.

## Requirements
- pkg-config
- libjpeg-dev
- libpng-dev
- libcurl4-openssl-dev
- libxml2-dev
- libtidy-dev
- php*.*-dev

## Download & Compilation

```php
<?php

use Versyx\Jail\Downloader;
use Versyx\Jail\Compiler;

require __DIR__ . '/../config/bootstrap.php';

$debug = true;
$version = "7.1.30";
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
        // If you already have the archive, you can call the compiler
        // directly, e.g. $compiler->compile("7.3.3", "/tmp/php-7.3.3");
        $compiler->compile($php->getVersion(), $php->getTarget());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
```

## TODO

Complete the jailer module.

## Example Deployment

![example deployment](https://rowles.ch/images/codepad.jpg)
