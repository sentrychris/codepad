<?php

namespace Crowles\App;

/**
 * Base.
 *
 * This is the base class for App, all classes must
 * extend this class.
 *
 * @package Crowles\App
 */
class Base
{

    /**
     * @var bool $debug
     */
    protected $debug;

    /**
     * Base constructor.
     * @param $debug
     */
    public function __construct($debug)
    {
        if (!is_null($debug)) {
            $this->debug = (bool) $debug;
        }
    }

    /**
     * @return mixed
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param mixed $debug
     */
    public function setDebug($debug): void
    {
        $this->debug = $debug;
    }

}