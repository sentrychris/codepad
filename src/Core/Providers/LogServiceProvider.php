<?php

namespace Versyx\Codepad\Core\Providers;

use Monolog\Logger;
use Pimple\Container;
use Monolog\Handler\StreamHandler;
use Pimple\ServiceProviderInterface;

/**
 * Class LogServiceProvider
 */
class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * Register log service provider.
     *
     * @param Container $pimple
     * @return Container|string
     */
    public function register(Container $pimple)
    {
        $pimple['log'] = new Logger('app');

        try {
            $pimple['log']->pushHandler(new StreamHandler($this->logPath(), Logger::DEBUG));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $pimple;
    }

    /**
     * Resolve log path.
     *
     * @return string
     */
    public function logPath()
    {
        return __DIR__ . '/../../../logs/app.log';
    }
}