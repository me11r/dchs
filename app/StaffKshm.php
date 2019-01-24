<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffKshm
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffKshm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffKshm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffKshm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffKshm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffKshm extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];
}
