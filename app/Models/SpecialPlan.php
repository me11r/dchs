<?php

namespace App\Models;

use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\FireDepartment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @mixin \Eloquent | Builder
 * @property string|null $year_of_development
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SpecialPlan whereYearOfDevelopment($value)
 */
class SpecialPlan extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'special_plans';

    public $fillable = [
        'fire_level_id',
        'city_area_id',
        'object_name',
        'fire_department_id',
        'operational_plan_id',
        'location',
        'year_of_development',
    ];

    public function fire_level()
    {
        return $this->belongsTo(FireLevel::class, 'fire_level_id');
    }

    public function city_area()
    {
        return $this->belongsTo(CityArea::class, 'city_area_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function operational_plan()
    {
        return $this->belongsTo(OperationalPlan::class, 'operational_plan_id');
    }
}
