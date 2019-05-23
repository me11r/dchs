<?php

namespace App\Models;

use App\FireDepartment;
use App\VehicleClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vehicle
 *
 * @property int $id
 * @property string|null $note
 * @property string|null $reg_certificate номер свидетельства регистрации
 * @property string|null $number_old
 * @property int|null $publish_year
 * @property string|null $purpose
 * @property string|null $base На базе (шасси)
 * @property string|null $name
 * @property string|null $number
 * @property int|null $fire_department_id
 * @property int|null $vehicle_type_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment|null $fireDepartment
 * @property-read \App\Models\VehicleType|null $vehicleType
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereNumberOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle wherePublishYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereRegCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehicle whereVehicleTypeId($value)
 * @mixin \Eloquent
 */
class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'number',
        'fire_department_id',
        'vehicle_type_id',
        'base',
        'purpose',
        'publish_year',
        'number_old',
        'reg_certificate',
        'note',
        'vehicle_class_id',
    ];


    public function fireDepartment()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function vehicleClass()
    {
        return $this->belongsTo(VehicleClass::class, 'vehicle_class_id');
    }
}
