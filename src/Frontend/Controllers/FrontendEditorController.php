<?php

namespace Versyx\Codepad\Frontend\Controllers;

/**
 * Home controller class.
 */
class FrontendEditorController extends Controller
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
        $viewData = $this->viewData($data);
        return $this->render('editors/frontend.twig', $viewData);
    }
}
