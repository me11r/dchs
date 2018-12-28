<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffCrb
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCrb whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCrb whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCrb whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCrb whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffCrb extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
    ];
}
