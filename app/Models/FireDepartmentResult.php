<?php

namespace App\Models;

use App\FireDepartment;
use App\RoadtripPlan;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FireDepartmentResult extends Model
{
    protected $fillable = [
        'ticket101_id',
        'fire_department_id',
        'out_time',
        'arrive_time',
        'loc_time',
        'liqv_time',
        'ret_time',
        'dispatched',
        'dispatch_id',
        'departments',
    ];

    public function getOutTimeAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('H:i');
        }
        return $value;
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function road_trip_plan()
    {
        return $this->belongsTo(RoadtripPlan::class, 'dispatch_id');
    }
}
