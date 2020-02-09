<?php

use Klein\Klein as Router;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Versyx\Codepad\Console\ChrootManager;
use Versyx\Codepad\Console\Compiler;
use Versyx\Codepad\Console\Downloader;

/*----------------------------------------
| Create application container           |
----------------------------------------*/
$app = new Container();

/*----------------------------------------
| Load application dependencies           |
-----------------------------------------*/
$app['downloader'] = function () {
    $downloader = new Downloader(env('APP_DEBUG'));

    return $downloader;
};

$app['compiler'] = function () {
    $compiler = new Compiler(env('APP_DEBUG'));

    return $compiler;
};

$app['chroot-manager'] = function () {
    $cm = new ChrootManager(env('APP_DEBUG'));

    return $cm;
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
        'cache' => false,
        'debug' => env('APP_DEBUG'),
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
