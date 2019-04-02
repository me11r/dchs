<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Weather
 *
 * @property int $id
 * @property string $date
 * @property string $file
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $note
 * @property string|null $forecast_atmosphere
 * @property string|null $forecast_water
 * @property string|null $city2_abs_min
 * @property string|null $city2_abs_max
 * @property string|null $forecast_city2
 * @property string|null $city1_abs_min
 * @property string|null $city1_abs_max
 * @property string|null $forecast_city1
 * @property string|null $forecast_area
 * @property string|null $executor
 * @property string|null $filial_director
 * @property string|null $address
 * @property string|null $atmosphere_now
 * @property string|null $radiation_now
 * @property string|null $water_now
 * @property string|null $weather_now
 * @property string|null $number
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather date($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather today()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereAtmosphereNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCity1AbsMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCity1AbsMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCity2AbsMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereCity2AbsMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereExecutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereFilialDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereForecastArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereForecastAtmosphere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereForecastCity1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereForecastCity2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereForecastWater($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereRadiationNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereWaterNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Weather whereWeatherNow($value)
 */
class Weather extends Model
{
    public $table = 'weather';

    public $fillable = [
        'date',
        'file',
        'number',
        'weather_now',
        'water_now',
        'radiation_now',
        'atmosphere_now',
        'address',
        'filial_director',
        'executor',
        'forecast_area',
        'forecast_city1',
        'city1_abs_max',
        'city1_abs_min',
        'forecast_city2',
        'city2_abs_max',
        'city2_abs_min',
        'forecast_water',
        'forecast_atmosphere',
        'note',
        'storm_warning_number',
        'storm_warning_date',
        'storm_warning_text',
    ];

    public function scopeDate($q, $search)
    {
        return $q->whereDate('date', $search);
    }

    public function scopeToday($q)
    {
        return $q->date(today());
    }

    //attribute: storm_info
    public function getStormInfoAttribute()
    {
        $date = $this->storm_warning_date ? Carbon::parse($this->storm_warning_date)->format('d.m.Y') : '';
        return "Номер: {$this->storm_warning_number}, дата: {$date}, {$this->storm_warning_text}";
    }

    //attribute: storm_date_formatted
    public function getStormDateFormattedAttribute()
    {
        return $this->storm_warning_date ? Carbon::parse($this->storm_warning_date)->format('d.m.Y') : null;
    }
}
