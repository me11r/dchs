<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class Ticket101Other extends Model
{
    protected $fillable = [
        'ride_type_id',
        'ticket_101_id',
        'fire_department_id',
        'department',
        'time_begin',
        'time_end',
        'object_name',
        'staff_id',
        'note',
        'direction',
    ];

    public function ride_type()
    {
        return $this->belongsTo(RideType::class,'ride_type_id');
    }

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class,'ticket_101_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class,'staff_id');
    }
}
