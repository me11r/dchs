<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\OperationalPlan
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperationalPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperationalPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperationalPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OperationalPlan whereUpdatedAt($value)
 * @mixin \Eloquent | Builder
 */
class OperationalPlan extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $table = 'dict_operational_plan';

    public $fillable = ['name'];
}
