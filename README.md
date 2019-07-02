[WIP]

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

## Downloading & Compiling

```php
<?php

use Versyx\Jail\Downloader;
use Versyx\Jail\Compiler;

require __DIR__ . '/../config/bootstrap.php';

$debug = true;
$version = "7.1.30";
init(new Downloader($debug), new Compiler($debug), $version);

function init(Downloader $downloader, Compiler $compiler, string $version)
{
    try {
        $php = $downloader->setVersion($version)->download();
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

### How it Works

The PHP code and version is base64 encoded and submitted to `http/manager.php`, the manager then 
base64 decodes the data and runs a check on the code input against disabled functions, if the check
comes back clean, a new process is created with stream resources:

```php
$proc = proc_open("sudo /opt/phpjail/php-$ver/bin/php /var/www/php-jailer/http/worker.php $ver", [
    0 => ["pipe", "rb"],
    1 => ["pipe", "wb"],
    2 => ["pipe", "wb"]
], $pipes);
```

The PHP code is passed to `http/worker.php` from the manager via STDIN, the worker then creates a temporary file in
`/opt/phpjail`, sets its permissions to `0444` and then executes the file using the selected PHP version
instance, which is chrooted to `/opt/phpjail` as user `nobody`. If the code takes longer than five seconds to execute, 
the process will terminate.

```php
$starttime = microtime(true);
$unused = [];
$ph = proc_open('chroot --userspec=nobody /opt/phpjail /php-' . $argv[1] .'/bin/php ' . escapeshellarg(basename($file)), $unused, $unused);
$terminated = false;
while (($status = proc_get_status($ph)) ['running']) {
    usleep(100 * 1000);
    if (!$terminated && microtime(true) - $starttime > MAX_RUNTIME_SECONDS) {
        $terminated = true;
        echo 'max runtime reached (' . MAX_RUNTIME_SECONDS . ' seconds), terminating...';
        pKill($status['pid']);
    }
}

proc_close($ph);
```

## Example Deployment

![example deployment](https://rowles.ch/images/codepad.jpg)
