<?php

namespace Versyx\Codepad\Cli;

/**
 * PHP Jailer.
 *
 * This class is responsible for isolating compiled PHP versions
 * to configured chroot jails.
 *
 * @author Chris Rowles <me@rowles.ch>
 */
class Jailer extends Base
{
    /** @var $root */
    protected $root;

    /** @var $fs */
    protected $fs;

    /**
     * Jailer constructor.
     *
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     * Build jail.
     *
     * @param string $version
     */
    public function buildJail(string $version)
    {
        if (!file_exists($this->root)) {
            if ($this->isDebug()) {
                echo 'Creating jail...'.PHP_EOL;
            }
            mkdir($this->root);
        }

        foreach ($this->fs as $device) {
            $device = substr($device, 0, 1) === '/' ? $device : '/'.$device;

            if (!file_exists($this->root.$device)) {
                if ($this->isDebug()) {
                    echo 'Creating '.$this->root.$device.PHP_EOL;
                }
                mkdir($this->root.$device);
            }
        }
    }

    /**
     * Mount all devices.
     */
    public function mountAll()
    {
        foreach ($this->fs as $device) {
            $device = substr($device, 0, 1) === '/' ? $device : '/'.$device;
            echo 'Mounting '.$device.'...'.PHP_EOL;
            $this->mount($device, $this->root.$device, 'bind', 'ro');
        }
    }

    /**
     * Mounts filesystem.
     *
     * @param array  $opt
     * @param string $device
     * @param string $point
     */
    public function mount(string $device, string $point, ...$opt)
    {
        $opt = implode(',', $opt);
        shell_exec("mount -o $opt $device $point 2>&1");
    }

    /**
     * Fetch a device.
     *
     * @param string $device
     *
     * @return array
     */
    public function getDevice(string $device): array
    {
        return $this->fs[$device];
    }

    /**
     * Set devices to be added.
     *
     * @param mixed $device
     *
     * @return void
     */
    public function setDevices(...$device): void
    {
        $this->fs = $device;
    }

    /**
     * Fetch root.
     *
     * @return array
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set root.
     *
     * @param mixed $device
     *
     * @return void
     */
    public function setRoot(string $root): void
    {
        $this->root = $root;
    }

    /**
     * mkdir.
     *
     * @param string $device
     *
     * @return void
     */
    public function mkJailDir(string $device): void
    {
        mkdir($this->root.$device);
    }

    /**
     * chmod recursive.
     *
     * @param mixed $mode
     */
    public function setPermissions($mode)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root)
        );

        foreach ($iterator as $item) {
            chmod($item, $mode);
        }
    }

    /**
     * chown recursive.
     *
     * @param string $owner
     * @param string $group
     */
    public function setOwnership(string $owner, string $group)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root)
        );

        foreach ($iterator as $item) {
            chown($item, $owner);
            chgrp($item, $group);
        }
    }
}
