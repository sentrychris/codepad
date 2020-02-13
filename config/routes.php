<?php

/*----------------------------------------
| Configure application routes           |
----------------------------------------*/
/** @var \Versyx\Codepad\Frontend\Controllers\HomeController $home */
$app['router']->respond('GET', '/', function () use ($home) {
    return $home->view(['title' => 'PHP Editor']);
});

/** @var \Versyx\Codepad\Frontend\Controllers\FrontendEditorController $frontend */
$app['router']->respond('GET', '/frontend', function () use ($frontend) {
    return $frontend->view(['title' => 'Frontend Editor']);
});
