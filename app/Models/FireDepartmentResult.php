<?php

namespace App\Models;

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
}
