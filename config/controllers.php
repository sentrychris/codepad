<?php

/*----------------------------------------
 | Register application controllers       |
 ----------------------------------------*/
 $controllers = [
     'home' => new \Versyx\Codepad\Frontend\Controllers\HomeController($app),
 ];

return extract($controllers);
