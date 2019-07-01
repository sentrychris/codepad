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