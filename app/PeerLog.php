<?php

namespace App;

use Endroid\QrCode\QrCode;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PeerLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $public_key
 * @property string $preshared_key
 * @property string $endpoint
 * @property string $allowed_ips
 * @property int $latest_handshake
 * @property int $transfer_rx
 * @property int $transfer_tx
 * @property string $persistent_keepalive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereAllowedIps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereLatestHandshake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog wherePersistentKeepalive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog wherePresharedKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereTransferRx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereTransferTx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PeerLog whereUpdatedAt($value)
 */
class PeerLog extends Model
{
    protected $guarded = [];
    protected $appends = ["peer"];

    public function getPeerAttribute()
    {
        return AvailableIp::where("public_key", $this->public_key)->first();
    }
}
