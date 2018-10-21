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
 * @property int $id
 * @property float|null $long
 * @property float|null $lat
 * @property string|null $name
 * @property int $city_area_id
 * @property string|null $building_number
 * @property int|null $street_id
 * @property int|null $city_micro_area_id
 * @property int|null $object_type_id
 * @property string|null $year_of_development
 * @property int|null $number_of_storeys
 * @property float|null $square
 * @property float|null $square_total
 * @property int|null $wall_material_id
 * @property string|null $features
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCityMicroAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereNumberOfStoreys($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereObjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereSquareTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereStreetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereWallMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Building whereYearOfDevelopment($value)
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
                $query->where('name', '=', "$location");
            });
        }
        else{
            return $q->where('building_number', '=', "$home")
                ->whereHas('street', function ($query) use($location){
                $query->where('name', 'like', "%$location%");
            })->orWhereHas('city_micro_area', function ($query) use($location){
                $query->where('name', '=', "$location");
            });
        }
    }
}
