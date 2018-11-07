<?php

namespace App;

use App\Models\FireDepartmentResult;
use Illuminate\Database\Eloquent\Model;

class Arrived101 extends Model
{
    protected $fillable = [
        'ticket101_id',
        'event_info_arrived_id',
        'working_time',
        'quantity',
        'information',
        'fire_department_result_id',
    ];

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function event_info()
    {
        return $this->belongsTo(EventInfoArrived::class, 'event_info_arrived_id');
    }

    public function fire_department_result()
    {
        return $this->belongsTo(FireDepartmentResult::class, 'fire_department_result_id');
    }
}
