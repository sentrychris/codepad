<?php

namespace Versyx\Codepad;

use Exception;

/**
 * PHP Downloader.
 *
 * This class is responsible for downloading bzip2 archives for
 * specified PHP versions.
 *
 * @author Chris Rowles <me@rowles.ch>
 */
class Downloader extends Base
{
    /** @var $url string */
    protected $url = 'https://www.php.net/distributions/';

    /** @var $version string */
    protected $version;

    /** @var $target string */
    protected $target;

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
     * PHP Version downloader.
     *
     * @throws Exception
     *
     * @return Downloader
     */
    public function download() : self
    {
        $file = "php-$this->version.tar.bz2";
        $this->setTarget(@tempnam(sys_get_temp_dir().'/'.$file, 'php'));

        if (!preg_replace('/\W+|php/', '', $this->version)) {
            throw new Exception('Invalid Version');
        }

        if ($this->isDebug()) {
            echo "Fetching $file from $this->url\n...";
        }

        $resource = fopen($this->target, 'w');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.$file);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FILE, $resource);
        $result = curl_exec($ch);
        if (!$result) {
            echo 'Error :- '.curl_error($ch);
        } else {
            echo "$file successfully downloaded from $this->url";
        }
        curl_close($ch);

        if ($this->isDebug()) {
            echo "\nStored $this->version archive as $this->target\n";
        }

        return $this;
    }

    /**
     * Returns the PHP version.
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets the PHP version.
     *
     * @param mixed $version
     *
     * @return Downloader
     */
    public function setVersion($version) : self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Returns the download URL.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the download URL.
     *
     * @param mixed $url
     *
     * @return Downloader
     */
    public function setUrl($url) : self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Returns the target.
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets the target.
     *
     * @param mixed $target
     *
     * @return Downloader
     */
    public function setTarget($target) : self
    {
        $this->target = $target;

        return $this;
    }
}
