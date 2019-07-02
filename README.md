[WIP]

This is a tool for downloading, compiling and deploying PHP versions to chroot jails.

## Requirements
- pkg-config
- libjpeg-dev
- libpng-dev
- libcurl4-openssl-dev
- libxml2-dev
- libtidy-dev
- php*.*-dev

## Quick install

```bash
$ ./install 7.1.30
```

## Example Code

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

## Deploying

### Creating the chrooted Jail

The jailer module is incomplete, in the meantime please follow these instructions:

Create the jail:
```bash
$ mkdir /opt/phpjail
$ cd /opt/phpjail
```

Create the filesystem
```bash
$ mkdir bin etc dev lib lib64 usr
$ mkdir etc/alternatives
```

Create mount points for PHP versions
```bash
$ mkdir php-7.0.33 php-7.1.30 php-7.2.19...
```

Set permissions
```bash
$ chmod -R 0711 /opt/phpjail
$ chown -R root:root /opt/phpjail
```

Mount filesystem in read-only mode
```bash
mount -o bind,ro /bin /opt/phpjail/bin
mount -o bind,ro /dev /opt/phpjail/dev
mount -o bind,ro /lib /opt/phpjail/lib
mount -o bind,ro /lib64 /opt/phpjail/lib64
mount -o bind,ro /usr /opt/phpjail/usr
mount -o bind,ro /etc/alternatives /opt/phpjail/etc/alternatives
mount -o bind,ro /tmp/php-7.0.33 /opt/phpjail/php-7.0.33
mount -o bind,ro /tmp/php-7.1.30 /opt/phpjail/php-7.1.30
mount -o bind,ro /tmp/php-7.2.19 /opt/phpjail/php-7.2.19
```

### Enabling the worker

You'll need to allow www-data to run the worker script as a privileged user, add the following entries to
 `/etc/sudoers`:

```
www-data ALL =(ALL) NOPASSWD: /opt/phpjail/php-7.3.6/bin/php /var/www/php-jailer/http/worker.php 7.3.6
www-data ALL =(ALL) NOPASSWD: /opt/phpjail/php-7.0.33/bin/php /var/www/php-jailer/http/worker.php 7.0.33
```

This will restrict `www-data`'s sudo privileges to only running the worker.

The worker will execute the script which is saved to a temporary file from STDIN, it
then executes the file using the selected PHP version instance which is chrooted to
`/opt/phpjail` as user `nobody`.

## Example Deployment

![example deployment](https://rowles.ch/images/codepad.jpg)
