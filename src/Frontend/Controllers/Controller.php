<?php

namespace Versyx\Codepad\Frontend\Controllers;

use Pimple\Container;

/**
 * Abstract base controller class.
 */
abstract class Controller
{
    /** @var mixed $log */
    protected $log;

    /** @var mixed $router */
    protected $router;

    /** @var mixed $view */
    protected $view;

    /** @var array $data */
    public $data = [];

    /**
     * Abstract Controller constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->log = $container['log'];
        $this->router = $container['router'];
        $this->view = $container['view'];
    }

    /**
     * Set data to pass to the view.
     *
     * @param array $data
     *
     * @return array
     */
    protected function viewData(array $data = []): array
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $this->data[$key] = $val;
            }
        }

        return $this->data;
    }

    /**
     * Renders templates with view data.
     *
     * @param string $template
     * @param array  $data
     *
     * @return mixed
     */
    protected function render(string $template, array $data)
    {
        return $this->view->render($template, $data);
    }
}