<?php

declare(strict_types=1);

require __DIR__ . '/../config/bootstrap.php';

if (isBase64($_POST['code']) && isBase64($_POST['ver'])) {
    $raw = (string)(base64_decode($_POST['code'] ?? ''));
    $ver = (string)(base64_decode($_POST['ver'] ?? ''));
} else {
    $raw = (string)($_POST['code'] ?? '');
    $ver = (string)($_POST['ver'] ?? '');
}

$banned = [
    "include",
    "require",
    "exec",
    "eval",
    "assert",
    "system",
    "passthru",
    "mkdir",
    "chdir",
    "chown",
    "file_",
];

// TODO improve this!
if(str_replace($banned, '', $raw) != $raw) {
    die("Not Allowed!");
}

$pipes = [];
$proc = proc_open("sudo /opt/phpjail/php-$ver/bin/php /var/www/php-jailer/http/worker.php", [
    0 => ["pipe", "rb"],
    1 => ["pipe", "wb"],
    2 => ["pipe", "wb"]
], $pipes);

fwrite($pipes [0], $raw);
fclose($pipes [0]);
while (($status = proc_get_status($proc)) ['running']) {
    usleep(100 * 1000); // 100 ms
    echo stream_get_contents($pipes [2]);
    echo stream_get_contents($pipes [1]);
}

echo stream_get_contents($pipes[2]);
fclose($pipes[2]);

echo stream_get_contents($pipes[1]);
fclose($pipes[1]);

proc_close($proc);