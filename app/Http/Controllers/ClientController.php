<?php

namespace App\Http\Controllers;

use App\AvailableIp;
use App\Util\WireGuardWrapper;
use Composer\Config;
use Illuminate\Http\Request;
use Validator;

class ClientController extends Controller
{
    public function addClient()
    {
        $wireGuardServerPublicIp = config('wireguard.WIREGUARD_PUBLIC_IP');
        $wireGuardServerPublicKey = config('wireguard.WIREGUARD_PUBLIC_KEY');
        $listenPort = config('wireguard.WIREGUARD_LISTEN_PORT');
        $keyPair = WireGuardWrapper::getInstance()->generateKeyPair();
        $ip = AvailableIp::where('is_assigned', false)->first();

        if (!$ip) {
            return response()->json(["success" => false, "status" => "error", "message" => "IP range exhausted."]);
        }
        $ipToAssign = "$ip->ip/32";

        WireGuardWrapper::getInstance()->addClientToServer($keyPair[0], $ipToAssign);

        $config = <<<EOD
[Interface]
PrivateKey = $keyPair[1]
Address = $ipToAssign
DNS = 8.8.8.8

[Peer]
PublicKey = $wireGuardServerPublicKey
Endpoint = $wireGuardServerPublicIp:$listenPort
AllowedIPs = 0.0.0.0/0
PersistentKeepalive = 25
EOD;
        $ip->public_key = $keyPair[0];
        $ip->endpoint = $ipToAssign;
        $ip->is_assigned = true;
        $ip->save();
        return response()->json(["config" => $config, "key_pair" => $keyPair]);
    }

    public function removeClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "public_key" => "required",
        ]);

        if ($validator->fails())
            return response()->json(["success" => false, "status" => "error", "message" => "Invalid input"]);

        $publicKey = $request->query("public_key");
        $ip = AvailableIp::where('public_key', $publicKey)->first();
        if ($ip) {

            WireGuardWrapper::getInstance()->removeClientFromServer($publicKey);

            $ip->public_key = null;
            $ip->endpoint = null;
            $ip->is_assigned = false;
            $ip->save();
            return response()->json(["success" => true, "status" => "ok", "message" => "Client removed from server"]);
        } else {
            return response()->json(["success" => false, "status" => "error", "message" => "No such peer exists."]);
        }
    }
}
