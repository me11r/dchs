<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;

class FormationDistrictManagerItem extends Model
{
    protected $fillable = [
        'report_id',
        'manager_id',
        'city_area_id',
    ];

    public function report()
    {
        return $this->belongsTo(FormationDistrictManager::class, 'report_id');
    }

    public function manager()
    {
        return $this->belongsTo(DistrictManager::class, 'manager_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }
}
