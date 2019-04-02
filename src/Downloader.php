<?php

namespace Crowles\Cophi;

use Exception;

/**
 * PHP Downloader.
 *
 * This class is responsible for downloading and providing bzip2 archives
 * for specified PHP versions.
 *
 * @package Crowles\Cophi
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Downloader extends Base
{

    /**
     * Downloader constructor.
     *
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     *
     * Version downloader.
     *
     * @param $version
     * @return array|bool
     * @throws Exception
     */
    public function download($version)
    {
        $url = "https://www.php.net/distributions/";
        $file = "php-{$version}.tar.bz2";

        $target = sys_get_temp_dir() .  "/" . $file;

        if (!preg_replace('/\W+|php/', '', $version)) {
            throw new Exception('Invalid Version');
        }

        if ($this->isDebug()) {
            echo "Fetching {$file} from {$url}\n...";
        }

        $resource = fopen($target, "w");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.$file);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FILE, $resource);
        $result = curl_exec($ch);
        if(!$result) {
            echo "Error :- ".curl_error($ch);
        }
        curl_close($ch);

        if ($this->isDebug()) {
            echo "\nStored {$version} archive as {$target}\n";
        }

        $data = [
            'version' => $version,
            'url' => $url,
            'archive' => $target
        ];

        return $data;
    }
}