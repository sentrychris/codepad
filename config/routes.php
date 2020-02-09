<?php

/*----------------------------------------
| Configure application routes           |
----------------------------------------*/
/** @var \Versyx\Codepad\Frontend\Controllers\HomeController $home */
$app['router']->respond('GET', '/', function () use ($home) {
    return $home->view(['title' => 'Home']);
});
