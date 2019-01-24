<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffDspt
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDspt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDspt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDspt whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDspt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffDspt extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];
}
