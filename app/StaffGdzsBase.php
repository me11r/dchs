<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StaffGdzsBase
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffGdzsBase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffGdzsBase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffGdzsBase whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffGdzsBase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffGdzsBase extends StaffOd
{
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
    ];
}
