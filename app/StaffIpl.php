<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffIpl
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffIpl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffIpl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffIpl whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffIpl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffIpl extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];
}
