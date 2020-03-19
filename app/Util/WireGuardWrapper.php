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
    private $wireGuardInterface;

    private function __construct()
    {
        $this->ssh = new SSH2(config('wireguard.WIREGUARD_PUBLIC_IP'));
        $this->ssh->login(config('wireguard.WIREGUARD_USERNAME'), config('wireguard.WIREGUARD_PASSWORD'));
        $this->wireGuardInterface = config('wireguard.WIREGUARD_INTERFACE');
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

    public function addClientToServer($publicKey, $ip)
    {
        $this->executeCommand("sudo wg set $this->wireGuardInterface peer $publicKey allowed-ips $ip");
    }

    public function removeClientFromServer($publicKey)
    {
        $this->executeCommand("sudo wg set $this->wireGuardInterface peer $publicKey remove");
    }

    public function showWireGuardStatus()
    {
        return $this->executeCommand("sudo wg");
    }

    public function showWireGuardDump()
    {
        return $this->executeCommand("sudo wg show wg0 dump");
    }

    public function getWireguardTransferData()
    {
        return $this->executeCommand("sudo wg show wg0 transfer");
    }
}
