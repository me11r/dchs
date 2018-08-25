<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
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
    ];


    public function fireDepartment()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
