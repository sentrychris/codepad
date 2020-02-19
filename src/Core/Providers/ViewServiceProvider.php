<?php

namespace Versyx\Codepad\Core\Providers;

use Pimple\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Pimple\ServiceProviderInterface;
use Versyx\Codepad\Core\Extensions\Twig\AssetExtension;
use Versyx\Codepad\Core\Extensions\Twig\DotenvExtension;

/**
 * Class ViewServiceProvider
 */
class ViewServiceProvider implements ServiceProviderInterface
{
    /**
     * Register view service provider.
     *
     * @param Container $pimple
     * @return Container
     * @throws \Twig\Error\LoaderError
     */
    public function register(Container $pimple) : Container
    {
        $loader = new FilesystemLoader($this->viewPath());
        $pimple['view'] = new Environment($loader, [
            'cache' => env('APP_CACHE') ?? $this->cachePath(),
            'debug' => env('APP_DEBUG'),
        ]);
        $pimple['view']->addExtension(new DebugExtension());
        $pimple['view']->addExtension(new DotenvExtension());
        $pimple['view']->addExtension(new AssetExtension());

        return $pimple;
    }

    /**
     * Resolve view path.
     *
     * @return string
     */
    private function viewPath()
    {
        return __DIR__ . '/../../../resources/views';
    }

    /**
     * Resolve cache path.
     *
     * @return string
     */
    private function cachePath()
    {
        return __DIR__ . '/../../../public/cache';
    }
}