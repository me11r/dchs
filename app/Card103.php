<?php

namespace App;

use App\Dictionary\CityArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card103 extends Model
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
        'trip_result', //Госпитализация | Отказ
        'trip_result_add', //text
        'register_time',
        'object_name',
        'pre_information', //text
        'patient_name',
        'patient_age', //integer
        'patient_gender', //male|female
    ];

    protected $appends = [
        'patient_gender_title',
    ];

    public $genderList = [
        'male' => 'мужской',
        'female' => 'женский',
    ];

    public function getPatientGenderTitleAttribute()
    {
        $current = $this->patient_gender;
        if (isset($this->genderList[$current])) {
            return $this->genderList[$current];
        }

        return '';
    }

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
        return $this->hasMany(Card103RoadtripPlan::class, 'card103_id');
    }
}
