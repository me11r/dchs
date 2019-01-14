<?php

namespace App;

use App\Models\FormationTechItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleStatus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function formation_tech_items()
    {
        return $this->hasMany(FormationTechItem::class, 'vehicle_status_id');
    }
}
