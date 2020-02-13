<?php

/*----------------------------------------
| Register application controllers       |
----------------------------------------*/
$controllers = [
    'home' => new \Versyx\Codepad\Frontend\Controllers\HomeController($app),
    'frontend' => new \Versyx\Codepad\Frontend\Controllers\FrontendEditorController($app)
];

return extract($controllers);
