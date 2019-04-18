<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\StaffZhalin
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffZhalin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffZhalin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffZhalin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffZhalin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffZhalin extends StaffOd
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
