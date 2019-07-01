<?php
/**
 * Helper functions.
 */

/**
 * Access environment variables.
 *
 * @param $var
 *
 * @return array|false|string
 */
function env($var)
{
    return getenv($var);
}

/**
 * Kill processes.
 *
 * @param int $pid
 */
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

/**
 * Check if a string seems base64-encoded.
 *
 * @param $s
 *
 * @return bool
 */
function isBase64($s)
{
    return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
}