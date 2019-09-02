<?php

namespace Versyx\Codepad;

/**
 * Base.
 *
 * This is the base class for the app, all classes must
 * extend this class.
 *
 * @package Versyx\Codepad
 */
class Base
{

    /**
     * @var bool $debug
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