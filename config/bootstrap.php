<?php

require_once __DIR__.'/../vendor/autoload.php';

/*----------------------------------------
| Load environment variables             |
----------------------------------------*/
$dotenv = Dotenv\Dotenv::create(__DIR__.'/../')->overload();

/*----------------------------------------
| Load application dependencies          |
----------------------------------------*/
require __DIR__.'/../config/dependencies.php';

/*----------------------------------------
| Load application controllers           |
----------------------------------------*/
require __DIR__.'/../config/controllers.php';

/*----------------------------------------
| Load application routes                |
----------------------------------------*/
require __DIR__.'/../config/routes.php';

define('APP_VERSION', '1.0.0');
