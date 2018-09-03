<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VehicleType
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VehicleType name($name)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VehicleType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VehicleType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VehicleType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\VehicleType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VehicleType extends Model
{
    protected $fillable = [
        'name',
    ];

    public $types = [
        'Основная',
        'Специальная',
        'Вспомогательная',
    ];

    public function scopeName($q, $name)
    {
        return $q->where('name', $name);
    }
}
