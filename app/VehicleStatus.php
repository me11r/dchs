<?php

namespace App;

use App\Models\BaseModel;
use App\Models\FormationTechItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleStatus extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function formation_tech_items()
    {
        return $this->hasMany(FormationTechItem::class, 'vehicle_status_id');
    }
}
