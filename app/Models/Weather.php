<?php

namespace App\Models;

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
    ];

    public function scopeDate($q, $search)
    {
        return $q->whereDate('date', $search);
    }

    public function scopeToday($q)
    {
        return $q->date(today());
    }
}
