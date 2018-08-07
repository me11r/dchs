<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialPlan extends Model
{
    public $table = 'special_plans';

    public $fillable = ['fire_level_id', 'city_area_id', 'object_name', 'fire_department_id', 'operational_plan_id', 'location'];
}
