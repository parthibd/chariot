<?php

namespace App\Http\Controllers;

use App\Util\WireGuardWrapper;
use Composer\Config;
use phpseclib\Net\SSH2;
use Util\CIDR;
use Illuminate\Http\Request;


class ConfigController extends Controller
{
    public function getConfig()
    {
        $wireguardServerPublicIp = Config::get('wireguard.WIREGUARD_PUBLIC_IP');
        $listenPort = Config::get('wireguard.WIREGUARD_LISTEN_PORT');
        $config = <<<EOD
[Interface]
PrivateKey = $privateKey
Address = 192.168.66.2/32
DNS = 8.8.8.8

[Peer]
PublicKey =
Endpoint = $wireguardServerPublicIp:$listenPort
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = 25
EOD;
        return response($config);
    }

    private function getIpsInRange($ip, $cidrRange)
    {
        $cidr = explode("/", $cidrRange);
        $networkMask = CIDR::CIDRtoMask($cidr[1]);
        $arr = [];
        $maxPossibleHostCount = ip2long("255.255.255.255") - ip2long($networkMask);
        for ($i = 0; $i <= $maxPossibleHostCount; $i++) {
            $arr[] = long2ip((ip2long($cidr[0]) & ip2long($networkMask)) | ip2long(long2ip($i)));
        }
        var_dump($arr);
    }

    private function getNextAvailableIp($ip, $cidrRange)
    {
        $cidr = explode("/", $cidrRange);
        $networkMask = CIDR::CIDRtoMask($cidr[1]);
        $maxPossibleHostCount = ip2long("255.255.255.255") - ip2long($networkMask);
        $currentIp = $ip;
        $nextIp = long2ip((
                ip2long($currentIp) & ip2long($networkMask)) |
            ((ip2long($currentIp) & ip2long(long2ip($maxPossibleHostCount))) + 1));
        $isValidIp = CIDR::IPisWithinCIDR($nextIp, $cidrRange);
        return $isValidIp ? $nextIp : "No CIDR range exhausted";
    }

    private function allocateIp()
    {

    }

    public function sshConnect()
    {
        return response(WireGuardWrapper::getInstance()->showWireGuardStatus());
    }

    public function getNextIp(Request $request)
    {
        $ip = $request->query("ip");
        return response($this->getNextAvailableIp($ip, '192.168.66.0/24'));
    }
}
