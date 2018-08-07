<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ServiceType
 *
 * @property int $id
 * @property string $name
 * @property int $info
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ServiceType whereName($value)
 * @mixin \Eloquent
 */
class ServiceType extends Model
{
    public $table = 'service_types';

    public $timestamps = false;

    public $fillable = ['name'];
}
