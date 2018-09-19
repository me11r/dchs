<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 14:45
 */

namespace App\Dictionary;


use App\FireDepartment;
use App\Models\CityMicroArea;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\CityArea
 *
 * @mixin \Eloquent | Builder
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CityMicroArea[] $city_micro_areas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea name($title)
 */
class CityArea extends Model
{
    protected $table = 'dict_city_area';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }

    public function city_micro_areas()
    {
        return $this->hasMany(CityMicroArea::class, 'city_area_id');
    }

    public function fire_departments()
    {
        return $this->hasMany(FireDepartment::class, 'city_area_id');
    }

}
