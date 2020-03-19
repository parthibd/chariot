<?php

namespace App\Console\Commands;

use App\AvailableIp;
use App\PeerLog;
use App\Util\WireGuardWrapper;
use Exception;
use Illuminate\Console\Command;

class RemoveInactivePeers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chariot:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes inactive peers after a timeout disabling access with the key-pair generated earlier';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dump = explode("\n", trim(WireGuardWrapper::getInstance()->showWireGuardDump()));
        array_shift($dump); // First line is for the server , so not necessary
        $peerLog = [];
        for ($i = 0; $i < sizeof($dump); $i++) {
            $peerLog[] = preg_split('/\s+/', $dump[$i]);
        }

        foreach ($peerLog as $peer) {
            $log = PeerLog::where("public_key", $peer[0])->first();
            if ($log) {
                $deltaHandshake = time() - $log->latest_handshake;
                $deltaTransfer = $peer[5] - $log->transfer_rx;
                if ($deltaHandshake >= 24 * 60 * 60 && $deltaTransfer == 0) {
                    WireGuardWrapper::getInstance()->removeClientFromServer($peer[0]);

                    $ip = AvailableIp::where('public_key', $peer[0])->first();
                    $ip->public_key = null;
                    $ip->endpoint = null;
                    $ip->is_assigned = false;
                    $ip->config = null;
                    $ip->name = null;
                    $ip->save();
                }
            }
            try {
                PeerLog::updateOrCreate(["public_key" => $peer[0]], [
                    "public_key" => $peer[0],
                    "preshared_key" => $peer[1],
                    "endpoint" => $peer[2],
                    "allowed_ips" => $peer[3],
                    "latest_handshake" => $peer[4],
                    "transfer_rx" => $peer[5],
                    "transfer_tx" => $peer[6],
                    "persistent_keepalive" => $peer[7],
                ]);
            } catch (Exception $exception) {
                print $exception;
            }
        }
    }
}
