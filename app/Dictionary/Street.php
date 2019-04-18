<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 18:20
 */

namespace App\Dictionary;


use App\Models\BaseModel;
use App\Models\CityMicroArea;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\Street
 *
 * @property int $id
 * @property int $city_area_id
 * @property string|null $name
 * @property-read \App\Dictionary\CityArea $area
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\Street whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\Street whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\Street whereName($value)
 * @mixin \Eloquent
 * @property int|null $city_micro_area_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\Street whereCityMicroAreaId($value)
 */
class Street extends BaseModel
{
    protected $table = 'streets';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'city_area_id',
        'city_micro_area_id',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id', 'id');
    }

    public static function createStreetIfNotExists($street_name, $area_name, $micro_area = null)
    {
        if($area_name){
            $area_entity = CityArea::name($area_name)->first();
            if(!$area_entity) return null;
            if($micro_area){
                $micro_area_arr = [
                    'name' => $micro_area,
                    'city_area_id' => $area_entity->id,
                ];
                $micro_area = CityMicroArea::firstOrCreate($micro_area_arr);
            }

            if($street_name){
                $street_arr = [
                    'name' => $street_name,
                    'city_area_id' => $area_entity->id,
                    'city_micro_area_id' => $micro_area? $micro_area->id : null,
                ];

                $street = Street::firstOrCreate([
                    'name' => $street_name,
                    'city_area_id' => $area_entity->id,
                ], $street_arr);
            }

            $result = [
                'city_area' => $area_entity ?? null,
                'micro_area' => $micro_area ?? null,
                'street' => $street ?? null,
            ];

            return $result;
        }

        return null;

    }


}