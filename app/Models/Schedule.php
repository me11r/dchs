<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'fire_department_main_id',
        'fire_department_id',
        'dict_fire_level_id',
        'is_reserved',
        'department',
    ];
}
