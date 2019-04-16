<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\StaffWaterCanal
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffWaterCanal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffWaterCanal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffWaterCanal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffWaterCanal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffWaterCanal extends StaffOd
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'guard_number_id',
    ];

    public $attributeNames = [
        'name' => 'Имя',
        'surname' => 'Фамилия',
        'patronymic' => 'Отчество',
        'guard_number_id' => 'Номер опер группы',
    ];
}
