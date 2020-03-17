<?php


namespace App\Util;


use phpseclib\Net\SSH2;

class WireGuardWrapper
{
    private static $instance;
    /**
     * @var SSH2
     */
    private $ssh;

    private function __construct()
    {
        $this->ssh = new SSH2('192.168.43.9');
        $this->ssh->login("parthib", "parthib");
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function executeCommand($command)
    {
        return $this->ssh->exec($command);
    }

    public function generateKeyPair()
    {
        $privateKey = $this->executeCommand('wg genkey');
        $publicKey = $this->executeCommand("echo '$privateKey' | wg pubkey");
        return [trim($publicKey), trim($privateKey)];
    }

    public function showWireGuardStatus()
    {
        return $this->executeCommand("sudo wg");
    }
}
