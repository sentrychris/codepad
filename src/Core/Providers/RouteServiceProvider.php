<?php

namespace Versyx\Codepad\Core\Providers;

use Klein\Klein;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class RouteServiceProvider
 */
class RouteServiceProvider implements ServiceProviderInterface
{
    /**
     * Register route service provider.
     *
     * @param Container $pimple
     * @return Container
     */
    public function register(Container $pimple)
    {
        $pimple['router'] = new Klein();

        return $pimple;
    }
}