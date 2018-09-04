<?php

namespace App\Models;

use App\Dictionary\BurntObject;
use App\Dictionary\CityArea;
use App\Dictionary\Street;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Building
 *
 * @property-read \App\Dictionary\CityArea $city_area
 * @property-read \App\Models\CityMicroArea $city_micro_area
 * @property-read \App\Dictionary\BurntObject $object_type
 * @property-read \App\Dictionary\Street $street
 * @property-read \App\Models\WallMaterial $wall_material
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building address($location, $home = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building name($title)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building number($number)
 * @mixin \Eloquent
 */
class Building extends Model
{
    protected $fillable = [
        'name',
        'city_area_id',
        'building_number',
        'street_id',
        'city_micro_area_id',
        'object_type_id',
        'year_of_development',
        'number_of_storeys',
        'square',
        'square_total',
        'wall_material_id',
        'features',
    ];

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id');
    }

    public function city_micro_area()
    {
        return $this->belongsTo(CityMicroArea::class, 'city_micro_area_id');
    }

    public function object_type()
    {
        return $this->belongsTo(BurntObject::class, 'object_type_id');
    }

    public function wall_material()
    {
        return $this->belongsTo(WallMaterial::class, 'wall_material_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }

    public function scopeNumber($q, $number)
    {
        return $q->where('building_number', $number);
    }

    public function scopeAddress($q, $location, $home = null)
    {
        if($home == null){
            return $q->whereHas('street', function ($query) use($location){
                $query->where('name', 'like', "%$location%");
            })->orWhereHas('city_micro_area', function ($query) use($location){
                $query->where('name', 'like', "%$location%");
            });
        }
        else{
            return $q->where('building_number', 'like', "$home%")
                ->whereHas('street', function ($query) use($location){
                $query->where('name', 'like', "%$location%");
            })->orWhereHas('city_micro_area', function ($query) use($location){
                $query->where('name', 'like', "%$location%");
            });
        }
    }
}
