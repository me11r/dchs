<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SpecialPlan
 *
 * @property int $id
 * @property int $fire_level_id
 * @property int $city_area_id
 * @property string $object_name
 * @property int $fire_department_id
 * @property int $operational_plan_id
 * @property string $location
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereCityAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereOperationalPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SpecialPlan extends Model
{
    public $table = 'special_plans';

    public $fillable = ['fire_level_id', 'city_area_id', 'object_name', 'fire_department_id', 'operational_plan_id', 'location'];
}
