<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleClass extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_class_id');
    }
}
