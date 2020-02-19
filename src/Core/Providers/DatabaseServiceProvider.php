<?php

namespace Versyx\Codepad\Core\Providers;

use Pimple\Container;
use Versyx\Codepad\Core\Database;
use Pimple\ServiceProviderInterface;

/**
 * Class DatabaseServiceProvider
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * Register database service provider.
     *
     * @param Container $pimple
     * @return Container
     */
    public function register(Container $pimple)
    {
        $pimple['db'] = new Database();

        return $pimple;
    }
}