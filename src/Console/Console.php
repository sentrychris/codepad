<?php

namespace Versyx\Codepad\Console;

use Pimple\Container;

/**
 * Base.
 *
 * This is the base class for the app, all classes must
 * extend this class.
 */
abstract class Console
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * Base constructor.
     *
     * @param $debug
     */
    public function __construct($debug)
    {
        if (!is_null($debug)) {
            $this->debug = (bool) $debug;
        }
    }

    /**
     * Check debug status.
     *
     * @return mixed
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Set debug status.
     *
     * @param mixed $debug
     */
    public function setDebug($debug): void
    {
        $this->debug = $debug;
    }
}
