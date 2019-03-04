<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MudflowProtection
 *
 * @property int $id
 * @property string|null $information
 * @property int $gauging_station_id
 * @property float|null $water_flow_rate
 * @property float|null $critical_water_flow_rate
 * @property float|null $turbidity_of_water
 * @property float|null $max_turbidity_of_water
 * @property float|null $air_temperature
 * @property float|null $water_temperature
 * @property float|null $precipitation
 * @property float|null $height_of_snow
 * @property string|null $weather
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\GaugingStation $gaugingStation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereAirTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereCriticalWaterFlowRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereGaugingStationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereHeightOfSnow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereMaxTurbidityOfWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection wherePrecipitation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereTurbidityOfWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereWaterFlowRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereWaterTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MudflowProtection whereWeather($value)
 * @mixin \Eloquent
 */
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
        'date',
    ];

    public function getDateHumanAttribute()
    {
        return $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null;
    }

    public function gaugingStation()
    {
        return $this->belongsTo(GaugingStation::class, 'gauging_station_id', 'id');
    }
}
