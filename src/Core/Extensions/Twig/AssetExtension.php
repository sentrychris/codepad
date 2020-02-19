<?php

namespace Versyx\Codepad\Core\Extensions\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Class AssetExtension
 */
class AssetExtension extends AbstractExtension
{

    /** @var string $path */
    private $path;

    /** @var string $asset */
    private $asset;

    /**
     * Define env accessor function.
     *
     * @return array
     */
    public function getFunctions()
    {
        $function = new TwigFunction('asset', [$this, 'asset'], []);

        $function->setArguments([]);

        return [$function];
    }

    /**
     * Generate assets url.
     *
     * @return string
     */
    public function asset()
    {
        return $this->url($_SERVER['REQUEST_URI'], func_get_args()[0]['file']);
    }

    /**
     * Generate a relative url from current path.
     *
     * @param string $uri
     * @param string $file
     * @return string
     */
    private function url($uri, $file)
    {
        if (!preg_match('%^/(?!.*/$)(?!.*[/]{2,})(?!.*\?.*\?)(?!.*\./).*%im', $uri)){;
            app('log')->error('Could not validate uri: ' . $uri . ' for file: ' . $file);
        }

        // TODO Handle consecutive slashes
        // RFC 2396 defines path separator to be single slash
        // However, URLs don't have to map to fs paths, so
        // rewriting or something will need to happen.

        if(substr_count($uri, '//') > 0) {
            app('log')->debug('true');
        }

        $depth = substr_count($uri, '/');
        $prefix = '/';
        if ($depth > 1) {
            for ($i=0; $i < $depth; $i++) {
                $prefix .= '../';
            }

            $this->path = $prefix;
        }

        $parts = pathinfo($file);

        switch ($parts['extension']) {
            case "gif":
            case "jpg":
            case "png":
            case "svg":
            case "webp":
                $this->asset = $this->path . 'images/' . $file;
                break;
            case "css":
                $this->asset = $this->path . 'css/' . $file;
                break;
            case "js":
                $this->asset = $this->path . 'js/' . $file;
                break;
            case "pdf":
                $this->asset = $this->path . 'docs/' . $file;
        }

        return $this->asset;
    }
}