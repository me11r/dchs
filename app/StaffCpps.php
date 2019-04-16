<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\StaffCpps
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $unique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCpps whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCpps whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCpps whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StaffCpps whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StaffCpps extends StaffOd
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
