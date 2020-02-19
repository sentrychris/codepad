<?php

namespace Versyx\Codepad\Core\Models;

use Pimple\Container;

/**
 * Abstract Class Model.
 */
abstract class Model
{
    /** @var mixed $log */
    protected $log;

    /** @var mixed $db */
    protected $db;

    /**
     * Model constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->log = $container['log'];
        $this->db = $container['db'];
    }
}
