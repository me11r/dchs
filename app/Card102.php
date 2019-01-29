<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card102 extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'closed',
        'location',
        'incident_type_id',
        'city_area_id',
        'detailed_address',
        'caller_phone',
        'caller_name',
        'call_time',
        'add_info',
        'add_info2',
        'trip_result',
        'trip_result_add', //text
        'register_time',
        'object_name',
        'pre_information', //text
    ];

    public function cityArea()
    {
        return $this->hasOne(CityArea::class, 'id', 'city_area_id');
    }

    public function service_plans()
    {
        return $this->hasMany(Ticket101ServicePlan::class, 'card103_id');
    }

    public function roadtrips()
    {
        return $this->hasMany(Card102RoadtripPlan::class, 'card102_id');
    }
}
