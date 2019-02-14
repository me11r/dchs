<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\StaffDoctor
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDoctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDoctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDoctor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffDoctor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffDoctor extends StaffOd
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];
}
