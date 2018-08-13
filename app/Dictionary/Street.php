<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 18:20
 */

namespace App\Dictionary;


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
 */
class Street extends Model
{
    protected $table = 'streets';
    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id', 'id');
    }
}