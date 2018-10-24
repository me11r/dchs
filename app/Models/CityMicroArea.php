<?php

namespace App\Models;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CityMicroArea
 *
 * @property-read \App\Dictionary\CityArea $city_area
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea name($title)
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int|null $city_area_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CityMicroArea whereUpdatedAt($value)
 */
class CityMicroArea extends Model
{
    protected $fillable = [
        'name',
        'city_area_id',
    ];

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
