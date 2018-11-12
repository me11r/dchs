<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 14:45
 */

namespace App\Dictionary;


use App\DistrictManager;
use App\FireDepartment;
use App\Models\CityMicroArea;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FireDepartment[] $fire_departments
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\CityArea onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\CityArea withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Dictionary\CityArea withoutTrashed()
 */
class CityArea extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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

    public function district_managers()
    {
        return $this->hasMany(DistrictManager::class, 'city_area_id');
    }

}
