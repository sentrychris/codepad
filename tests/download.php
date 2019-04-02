<?php
/**
 * Cophi PHP Downloader test.
 *
 * @author Chris Rowles
 * @package Cophi
 */

use Crowles\Cophi\Downloader;

require __DIR__ . '/../config/bootstrap.php';

init(new Downloader(), "7.3.3", true);

/**
 *
 * init test method.
 *
 * @param Downloader $downloader
 * @param $version
 * @param bool $debug
 */
function init(Downloader $downloader, $version, $debug = false)
{
    $downloader->setDebug($debug);

    try {
        $php = $downloader->download($version);

        if($debug) {
            echo "Successfully downloaded php (v{$php['version']}) from {$php['url']}).\n\n";
        }
    } catch (\Exception $e) {
        echo "ERROR: [DOWNLOAD] {$e->getMessage()}";
    }
}