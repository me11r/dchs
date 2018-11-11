<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistrictManager extends Model
{
    use SoftDeletes;

    protected $with = [
        'phones'
    ];

    protected $fillable = [
        'name',
        'rank',
        'nickname',
        'position',
        'city_area_id',
    ];

    public function phones()
    {
        return $this->hasMany(DistrictManagerPhone::class, 'district_manager_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }
}
