<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MudflowProtection extends Model
{
    public $table = 'mudflow_protections';

    public $fillable = [
        'information',
        'gauging_station_id',
        'water_flow_rate',
        'critical_water_flow_rate',
        'turbidity_of_water',
        'max_turbidity_of_water',
        'air_temperature',
        'water_temperature',
        'precipitation',
        'height_of_snow',
        'weather',
        'comment',
    ];

    public function gaugingStation()
    {
        return $this->belongsTo(GaugingStation::class, 'gauging_station_id', 'id');
    }
}
