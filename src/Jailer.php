<?php

namespace Versyx\Jail;

use Exception;

/**
 * PHP Jailer.
 *
 * This class is responsible for isolating compiled PHP versions
 * to configured chroot jails.
 *
 * @package Versyx\Jail
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Jailer extends Base
{
    /**
     * Jailer constructor.
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     *
     */
    public function deploy($instance)
    {}

    /**
     *
     */
    public function build()
    {}
}