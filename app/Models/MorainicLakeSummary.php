<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MorainicLakeSummary
 *
 * @property int $id
 * @property int $morainic_lake_id
 * @property string|null $initial_volume
 * @property string|null $current_volume
 * @property string|null $inflow_glacier
 * @property string|null $drainage_via_evacuation_channel
 * @property string|null $drainage_via_pump
 * @property string|null $drainage_via_siphon
 * @property string|null $water_dropped_day
 * @property string|null $water_dropped_total
 * @property string|null $lowering_from_initial1
 * @property string|null $lowering_from_initial2
 * @property string|null $temperature_water
 * @property string|null $temperature_air
 * @property string|null $zero_isotherm
 * @property string|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\MorainicLake $lake
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereCurrentVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereDrainageViaEvacuationChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereDrainageViaPump($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereDrainageViaSiphon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereInflowGlacier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereInitialVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereLoweringFromInitial1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereLoweringFromInitial2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereMorainicLakeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereTemperatureAir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereTemperatureWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereWaterDroppedDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereWaterDroppedTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MorainicLakeSummary whereZeroIsotherm($value)
 * @mixin \Eloquent
 */
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
