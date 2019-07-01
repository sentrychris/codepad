<?php

declare(strict_types=1);

const MAX_RUNTIME_SECONDS = 5;

if (posix_geteuid() !== 0) {
    fprintf(STDERR, "this script must run as root");
    die;
}

$code = stream_get_contents(STDIN);
if (!is_string($code)) {
    throw new \RuntimeException ('failed to read the code from stdin! (stream_get_contents failed)');
}

$file = tempnam("/opt/phpjail", "unsafe");
if (!is_string($file)) {
    throw new \RuntimeException ('tempnam failed!');
}

if (strlen($code) !== file_put_contents($file, $code)) {
    throw new \RuntimeException ('failed to write the code to disk! (out of diskspace?)');
}

if (!chmod($file, 0444)) {
    throw new \RuntimeException ('failed to chmod!');
}

$starttime = microtime(true);
$unused = [];
$ph = proc_open('chroot /opt/phpjail /php-7.3.6/bin/php ' . escapeshellarg(basename($file)), $unused, $unused);
$terminated = false;
while (($status = proc_get_status($ph)) ['running']) {
    usleep(100 * 1000);
    if (!$terminated && microtime(true) - $starttime > MAX_RUNTIME_SECONDS) {
        $terminated = true;
        echo 'max runtime reached (' . MAX_RUNTIME_SECONDS . ' seconds), terminating...';
        pKill(( int )($status ['pid']));
    }
}
echo "\nexit status: " . $status ['exitcode'];
proc_close($ph);

function pKill(int $pid)
{
    system("kill -s STOP " . $pid);
    $children = shell_exec('pgrep -P ' . $pid);

    if (is_string($children)) {
        $children = trim($children);
    }

    if (!empty ($children)) {
        $children = array_filter(array_map('trim', explode("\n", $children)), function ($in) {
            return false !== filter_var($in, FILTER_VALIDATE_INT);
        });
        foreach ($children as $child) {
            pKill(( int )$child);
        }
    }

    system("kill -s KILL " . $pid);
}