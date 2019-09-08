<?php

namespace Versyx\Codepad;

/**
 * PHP Jailer.
 *
 * This class is responsible for isolating compiled PHP versions
 * to configured chroot jails.
 *
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Jailer extends Base
{
    /** @var string $root */
    protected $root = '/opt/phpjail';

    /** @var array $fs */
    protected $fs = ['/bin', '/etc', '/dev', '/lib', '/lib64', '/usr'];

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
     * @param $version
     */
    public function build($version)
    {
        $this->addDevice("/php-$version");

        if (!file_exists($this->root)) {
            if ($this->isDebug()) {
                echo 'Creating jail...';
            }
            mkdir($this->root);
        }

        foreach ($this->fs as $device) {
            if (!file_exists($this->root.$device)) {
                if ($this->isDebug()) {
                    echo 'Creating filesystem...';
                }
                mkdir($this->root.$device);
            }

            echo 'Mounting filesystem...';
            $this->mount(['bind', 'ro'], $device, $this->root.$device);
        }
    }

    /**
     * Mounts filesystem.
     *
     * @param $opt
     * @param $device
     * @param $point
     */
    protected function mount($opt, $device, $point)
    {
        $opt = implode(',', $opt);
        shell_exec("mount -o $opt $device $point 2>&1");
    }

    /**
     * @return array
     */
    public function getDevice($device): array
    {
        return $this->fs[$device];
    }

    /**
     * @param string $device
     */
    public function addDevice(string $device): void
    {
        $this->fs[] = $device;
    }
}
