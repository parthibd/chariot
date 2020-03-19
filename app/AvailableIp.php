<?php

namespace App;

use Endroid\QrCode\QrCode;
use Illuminate\Database\Eloquent\Model;

/**
 * App\AvailableIp
 *
 * @property int $id
 * @property string $ip
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp whereIp($value)
 * @mixin \Eloquent
 * @property string|null $endpoint
 * @property string|null $public_key
 * @property int|null $is_assigned
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp whereIsAssigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp wherePublicKey($value)
 * @property string|null $config
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AvailableIp whereConfig($value)
 */
class AvailableIp extends Model
{
    public $timestamps = false;

    protected $appends = ["qr_code"];

    public function getQrCodeAttribute()
    {
        $qrCode = new QrCode($this->config);
        return 'data:image/' . $qrCode->getContentType() . ';base64,' . base64_encode($qrCode->writeString());
    }
}
