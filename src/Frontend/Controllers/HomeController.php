<?php

namespace Versyx\Codepad\Frontend\Controllers;

/**
 * Home controller class.
 */
class HomeController extends Controller
{
    /**
     * Render the home page.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function view(array $data = [])
    {
        $data['greeting'] = 'Hello World!';
        $viewData = $this->viewData($data);

        return $this->render('home.twig', $viewData);
    }
}
