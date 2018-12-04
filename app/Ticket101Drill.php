<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class Ticket101Drill extends Model
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
        'checked_pv',
        'checked_pg',
        'broken_pg',
        'broken_pv',
        'corrected_ok',
        'corrected_op',
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
