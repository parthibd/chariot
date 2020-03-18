<?php

namespace App;

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
 */
class AvailableIp extends Model
{
    public $timestamps = false;
}
