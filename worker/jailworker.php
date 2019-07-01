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

$file = tempnam(__DIR__, "unsafe");
if (!is_string($file)) {
    throw new \RuntimeException ('tempnam failed!');
}

register_shutdown_function(function () use (&$file) {
    if (!unlink($file)) {
        throw new \RuntimeException ('failed to clean up the file! (unlink failed!?)');
    }
});

if (strlen($code) !== file_put_contents($file, $code)) {
    throw new \RuntimeException ('failed to write the code to disk! (out of diskspace?)');
}

if (!chmod($file, 0444)) {
    throw new \RuntimeException ('failed to chmod!');
}

$starttime = microtime(true);
$unused = [];
$ph = proc_open('chroot --userspec=nobody /jail /usr/bin/php ' . escapeshellarg(basename($file)), $unused, $unused);
$terminated = false;
while (($status = proc_get_status($ph)) ['running']) {
    usleep(100 * 1000); // 100 ms
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
    system("kill -s STOP " . $pid); // stop it first, so it can't make any more children
    $children = shell_exec('pgrep -P ' . $pid);

    if (is_string($children)) {
        $children = trim($children);
    }

    if (!empty ($children)) {
        $children = array_filter(array_map('trim', explode("\n", $children)), function ($in) {
            return false !== filter_var($in, FILTER_VALIDATE_INT); // shouldn't be necessary, but just to be safe..
        });
        foreach ($children as $child) {
            pKill(( int )$child);
        }
    }

    system("kill -s KILL " . $pid);
}