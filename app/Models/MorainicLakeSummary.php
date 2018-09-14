<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MorainicLakeSummary extends Model
{
    protected $fillable = [
        'morainic_lake_id',
        'initial_volume',
        'current_volume',
        'inflow_glacier',

        'drainage_via_evacuation_channel',
        'drainage_via_pump',
        'drainage_via_siphon',
        'water_dropped_day',
        'water_dropped_total',

        'lowering_from_initial1',
        'lowering_from_initial2',

        'temperature_water',
        'temperature_air',

        'zero_isotherm',

        'date',
    ];

    public function lake()
    {
        return $this->belongsTo(MorainicLake::class, 'morainic_lake_id');
    }

    public function sumRecords($data, $field)
    {
        return $data->sum($field);
    }

}
