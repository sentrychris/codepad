<?php

use Monolog\Logger;
use Twig\Environment;
use Pimple\Container;
use Klein\Klein as Router;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Monolog\Handler\StreamHandler;

/*----------------------------------------
 | Create application container           |
 ----------------------------------------*/
 $app = new Container();

 /*----------------------------------------
  | Create application logger              |
  ----------------------------------------*/
 $app['log'] = function () {
     $log = new Logger('app');
     $log->pushHandler(
         new StreamHandler(__DIR__.'/../logs/app.log', Logger::DEBUG)
     );
 
     return $log;
 };
 
 /*----------------------------------------
  | Create application view renderer       |
  ----------------------------------------*/
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
 