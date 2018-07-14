<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 14:45
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Dictionary\CityArea
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Dictionary\CityArea whereUpdatedAt($value)
 */
class CityArea extends Model
{
    protected $table = 'dict_city_area';
    protected $guarded = ['id'];
}