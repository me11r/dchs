<?php
/**
 * Created by PhpStorm.
 * User: gorbunov
 * Date: 14.07.2018
 * Time: 18:20
 */

namespace App\Dictionary;


use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    protected $table = 'streets';
    protected $guarded = ['id'];

    public function area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id', 'id');
    }
}