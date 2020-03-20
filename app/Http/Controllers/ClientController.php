<?php

namespace App\Http\Controllers;

use App\AvailableIp;
use App\Util\WireGuardWrapper;
use Composer\Config;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use SplFileInfo;
use Storage;
use Validator;

class ClientController extends Controller
{
    public function addClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
        ]);

        if ($validator->fails())
            return response()->json(["success" => false, "status" => "error", "message" => "Invalid input"]);

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
        $ip->is_active = true;
        $ip->config = $config;
        $ip->name = $request->input("name");
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
        $ip = AvailableIp::where('public_key', 'LIKE', '%' . $publicKey . '%')->first();
        if ($ip) {
            WireGuardWrapper::getInstance()->removeClientFromServer($publicKey);

            $ip->public_key = null;
            $ip->endpoint = null;
            $ip->is_assigned = false;
            $ip->is_active = false;
            $ip->config = null;
            $ip->name = null;
            $ip->save();
            return response()->json(["success" => true, "status" => "ok", "message" => "Client removed from server"]);
        } else {
            return response()->json(["success" => false, "status" => "error", "message" => "No such peer exists."]);
        }
    }

    public function editClient($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
        ]);

        if ($validator->fails())
            return response()->json(["success" => false, "status" => "error", "message" => "Invalid input"]);

        $name = $request->input("name");
        $ip = AvailableIp::where('id', $id)->first();
        if ($ip) {
            $ip->name = $name;
            $ip->save();
            return response()->json(["success" => true, "status" => "ok", "message" => "Client edited!"]);
        } else {
            return response()->json(["success" => false, "status" => "error", "message" => "No such peer exists."]);
        }
    }

    public function getAllClients()
    {
        return response()->json(AvailableIp::where("is_assigned", true)->get());
    }

    public function toggleClientStatus($id)
    {
        $ip = AvailableIp::where('id', $id)->first();
        if ($ip) {
            if ($ip->is_active) {
                WireGuardWrapper::getInstance()->removeClientFromServer($ip->public_key);
                $ip->is_active = false;
                $ip->save();
            } else {
                WireGuardWrapper::getInstance()->addClientToServer($ip->public_key, $ip->endpoint);
                $ip->is_active = true;
                $ip->save();
            }
            return response()->json(["success" => true, "status" => "ok", "message" => "Client status toggled!"]);
        } else {
            return response()->json(["success" => false, "status" => "error", "message" => "No such peer exists."]);
        }
    }

    public function getClientConfigDownloadUrl($id)
    {
        return response()->json([
            'url' => URL::temporarySignedRoute(
                'downloadUserConfig', now()->addMinutes(30), ['id' => $id]
            )
        ]);
    }

    public function downloadConfigFile(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return response("Unauthorized", 401);
        }
        $ip = AvailableIp::where('id', $id)->first();
        if ($ip) {
            $headers = array(
                'Content-Type: text/plain',
            );
            Storage::disk('local')->put('config.conf', $ip->config);
            return response()->download(Storage::disk('local')->path('config.conf'), 'config.conf', $headers)->deleteFileAfterSend(true);
        } else {
            return response()->json(["success" => false, "status" => "error", "message" => "No such peer exists."]);
        }
    }
}
