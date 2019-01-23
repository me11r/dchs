<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property int $fire_department_main_id
 * @property int $fire_department_id
 * @property int $dict_fire_level_id
 * @property int $is_reserved
 * @property string|null $department
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereDictFireLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereFireDepartmentMainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereIsReserved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Schedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fire_department_main_id',
        'fire_department_id',
        'dict_fire_level_id',
        'is_reserved',
        'department',
    ];
}
