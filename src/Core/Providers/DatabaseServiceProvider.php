<?php

namespace Versyx\Codepad\Core\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Versyx\Codepad\Core\Database;

/**
 * Class DatabaseServiceProvider.
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * Register database service provider.
     *
     * @param Container $pimple
     *
     * @return Container
     */
    public function register(Container $pimple)
    {
        $pimple['db'] = new Database();

        return $pimple;
    }
}
