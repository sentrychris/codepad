<?php

declare(strict_types=1);

//header("content-type: text/plain;charset=utf8");

if (isBase64($_POST['code'])) {
    $raw = (string)(base64_decode($_POST['code'] ?? ''));
    $ver = (string)(base64_decode($_POST['version'] ?? ''));
} else {
    $raw = (string)($_POST['code'] ?? '');
    $ver = (string)($_POST['version'] ?? '');
}

$pipes = [];
$proc = proc_open("sudo /opt/phpjail/php-$ver/bin/php /var/www/php-jailer/worker/jailworker.php", [
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
