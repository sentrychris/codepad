<?php

namespace Versyx\Codepad\Console;

/**
 * Set up chroot environment for PHP versions.
 *
 * This class is responsible for isolating compiled PHP versions
 * to the configured chroot environment.
 *
 * @author Chris Rowles <me@rowles.ch>
 */
class ChrootManager extends Console
{
    /** @var $root */
    protected $root;

    /** @var $fs */
    protected $fs;

    /**
     * Chroot constructor.
     *
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     * Build chroot environment.
     *
     * @param string $version
     */
    public function buildChroot(string $version)
    {
        if (!file_exists($this->root)) {
            if ($this->isDebug()) {
                echo 'Creating '.$this->root.'...'.PHP_EOL;
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
            if ($this->isDebug()) {
                echo 'Mounting '.$device.' to '.$this->root.$device.'...'.PHP_EOL;
            }
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
        if ($this->isDebug() && debug_backtrace()[1]['function'] !== 'mountAll') {
            echo 'Mounting '.$device.' to '.$point.'...'.PHP_EOL;
        }
        shell_exec("mount -o $opt $device $point 2>&1");
    }

    /**
     * Fetch a device.
     *
     * @param string $device
     *
     * @return mixed
     */
    public function getDevice(string $device)
    {
        return $this->fs[$device];
    }

    /**
     * Fetch all devices.
     *
     * @param string $device
     *
     * @return mixed
     */
    public function getDevices()
    {
        return $this->fs;
    }

    /**
     * Set devices to be added.
     *
     * @param array $device
     *
     * @return void
     */
    public function setDevices(array $device): void
    {
        $this->fs = $device;
    }

    /**
     * Fetch root.
     *
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * Set root.
     *
     * @param string $root
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
    public function mkChrootdir(string $device): void
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
