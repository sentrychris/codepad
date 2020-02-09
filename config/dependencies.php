<?php

use Monolog\Logger;
use Twig\Environment;
use Pimple\Container;
use Klein\Klein as Router;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Monolog\Handler\StreamHandler;
use Versyx\Codepad\Console\Jailer;
use Versyx\Codepad\Console\Compiler;
use Versyx\Codepad\Console\Downloader;

/*----------------------------------------
| Create application container           |
----------------------------------------*/
$app = new Container();

/*----------------------------------------
| Load application dependencies           |
-----------------------------------------*/
$app['downloader'] = function() {
    $downloader = new Downloader(env("APP_DEBUG"));
    
    return $downloader;
};

$app['compiler'] = function() {
    $compiler = new Compiler(env("APP_DEBUG"));

    return $compiler;
};

$app['jailer'] = function() {
    $jailer = new Jailer(env("APP_DEBUG"));

    if(env("JAIL_DEVICES")) {
        $devices = explode(",", env("JAIL_DEVICES"));
        $jailer->setDevices($devices);
    }

    return $jailer;
};

$app['log'] = function () {
    $log = new Logger('app');
    $log->pushHandler(
        new StreamHandler(__DIR__.'/../logs/app.log', Logger::DEBUG)
    );

    return $log;
};

$app['view'] = function () {
    $loader = new FilesystemLoader(__DIR__.'/../resources/views');
    $view = new Environment($loader, [
        'cache' => env('CACHE') ? __DIR__.'/../public/cache' : env('CACHE'),
        'debug' => env('DEBUG'),
    ]);
    $view->addExtension(new DebugExtension());

    return $view;
};

/*----------------------------------------
| Create application router              |
----------------------------------------*/
$app['router'] = function () {
    $router = new Router();

    return $router;
};
