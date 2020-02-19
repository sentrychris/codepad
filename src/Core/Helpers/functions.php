<?php

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     *
     * @return mixed
     */
    function env($key)
    {
        $value = getenv($key);

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('toArray')) {
    /**
     * Helper method to convert objects to arrays.
     *
     * @param $object
     *
     * @return array
     */
    function toArray($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        return array_map('toArray', (array) $object);
    }
}

/**
 * Kill processes.
 *
 * @param int $pid
 */
function pKill(int $pid)
{
    system('kill -s STOP '.$pid);
    $children = shell_exec('pgrep -P '.$pid);

    if (is_string($children)) {
        $children = trim($children);
    }

    if (!empty($children)) {
        $children = array_filter(array_map('trim', explode("\n", $children)), function ($in) {
            return false !== filter_var($in, FILTER_VALIDATE_INT);
        });
        foreach ($children as $child) {
            pKill((int) $child);
        }
    }

    system('kill -s KILL '.$pid);
}

/**
 * Check if a string seems base64-encoded.
 *
 * @param string $string
 *
 * @return bool
 */
function isBase64(string $string)
{
    return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string);
}

/**
 * Print errors.
 *
 * @param string msg
 */
function error(string $msg)
{
    echo $msg.PHP_EOL;
    exit;
}
