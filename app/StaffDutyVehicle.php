<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffDutyVehicle
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDutyVehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDutyVehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDutyVehicle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDutyVehicle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffDutyVehicle extends StaffOd
{
    protected $fillable = [
        'name',
    ];
}
