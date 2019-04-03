<?php
// TODO Fix this class up.
namespace Crowles\Cophi;

use Exception;

/**
 * PHP Jailer.
 *
 * This class is responsible for isolating compiled PHP versions
 * to configured chroot jails.
 *
 * @package Crowles\Cophi
 * @author Chris Rowles <cmrowles@pm.me>
 */
class Jailer extends Base
{

    public $jailRoot = '/opt/cophi';
    public $jailkitPath = '/usr/sbin';
    public $jailUser = 'jailexec';
    public $webUser = 'www-data';

    /**
     * Jailer constructor.
     * @param $debug
     */
    public function __construct($debug = null)
    {
        parent::__construct($debug);
    }

    /**
     * @param $phpBase
     * @return mixed
     * @throws Exception
     */
    public function deploy($phpBase)
    {
        if ($this->isDebug()) {
            echo "Creating Jail {$this->jailRoot}\n";
        }

        $this->build();

        if ($this->isDebug()) {
            echo "Deploying {$phpBase} to {$this->jailRoot}\n";
        }

        $cmd = "sudo jk_cp -j {$this->jailRoot} {$phpBase} 2>&1";
        exec($cmd, $jail_log, $status);
        if ($status > 0) {
            throw new Exception('Failed to install PHP into jail: ' . implode("\n", $jail_log));
        }

        return $jail_log;
    }

    /**
     * @throws Exception
     */
    public function build()
    {
        $cmd = "sudo jk_init -j {$this->jailRoot} netutils basicshell jk_lsh openvpn 2>&1";
        exec($cmd, $init_log, $status);
        if ($status > 0) {
            throw new Exception('Failed to create jail: ' . implode("\n", $init_log));
        }

        if ($this->isDebug()) {
            echo "Initializing jail\n";
        }

        $cmd = "sudo useradd -NMd {$this->jailRoot}/ {$this->jailUser} 2>&1";
        exec($cmd, $user_log, $status);
        if ($status > 0) {
            throw new Exception('Failed to create user: ' . implode("\n", $user_log));
        }

        if ($this->isDebug()) {
            echo "Importing user into jail\n";
        }

        $cmd = "sudo jk_jailuser -j {$this->jailRoot} {$this->jailUser} 2>&1";
        exec($cmd, $jailuser_log, $status);
        if ($status > 0) {
            throw new Exception('Failed to jail user: ' . implode("\n", $jailuser_log));
        }

        if (file_exists($tmp = "{$this->jailRoot}/tmp")) {
            chmod($tmp, 0775);
        }
    }
}