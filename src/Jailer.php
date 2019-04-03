<?php

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
        if (!file_exists($this->jailRoot)) {
            if ($this->isDebug()) {
                echo "Creating Jail {$this->jailRoot}\n";
            }

            try {
                $this->buildJail($this->jailRoot);
            } catch (Exception $e) {
                throw new Exception('Failed to create jail: ' . $e->getMessage());
            }
        }

        if (!file_exists("{$this->jailRoot}/bin/bash")) {
            if ($this->isDebug()) {
                echo "Initializing jail\n";
            }

            $cmd = "{$this->jailkitPath}/jk_init -j {$this->jailRoot} netutils basicshell jk_lsh openvpn 2>&1";
            exec($cmd, $init_log, $return);

            if ($return) { // Not 0 = Failure
                throw new Exception('Failed to initialize jail: ' . implode("\n", $init_log));
            }

            $passwd = file_get_contents("{$this->jailRoot}/etc/passwd");
            if (strpos($passwd, $this->jailUser) === false) {

                $passwd = file_get_contents("/etc/passwd");
                if (strpos($passwd, $this->jailUser) === false) {
                    if ($this->isDebug()) {
                        echo "Adding User\n";
                    }

                    $cmd = "useradd -NMd {$this->jailRoot}/ {$this->jailUser}";
                    exec($cmd, $user_log, $return);

                    if ($return) { // Not 0 = Failure
                        throw new Exception('Failed to create user: ' . implode("\n", $user_log));
                    }
                }

                if ($this->isDebug()) {
                    echo "Importing user into jail\n";
                }
                $cmd = "{$this->jailkitPath}/jk_jailuser -j {$this->jailRoot} {$this->jailUser}";
                exec($cmd, $jailuser_log, $return);

                if ($return) { // Not 0 = Failure
                    throw new Exception('Failed to jail user: ' . implode("\n", $jailuser_log));
                }
            }

            if (!file_exists($tmp = "{$this->jailRoot}/tmp")) {
                if ($this->isDebug()) {
                    echo "Creating /tmp folder\n";
                }
                mkdir($tmp);
                exec("chown {$this->webUser}:{$this->jailUser} {$tmp}");
                chmod($tmp, 0775);
            }
        }

        if ($this->isDebug()) {
            echo "Deploying {$phpBase} to {$this->jailRoot}\n";
        }

        $cmd = "{$this->jailkitPath}/jk_cp -j {$this->jailRoot} {$phpBase} 2>&1";
        exec($cmd, $jail_log, $return);

        if ($return) { // Not 0 = Failure
            throw new Exception('Failed to install PHP into jail: ' . implode("\n", $jail_log));
        }

        return $jail_log;
    }
}
