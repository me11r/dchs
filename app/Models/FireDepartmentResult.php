<?php

namespace App\Models;

use App\FireDepartment;
use App\RoadtripPlan;
use App\Ticket101;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FireDepartmentResult
 *
 * @property int $id
 * @property int $ticket101_id
 * @property int|null $fire_department_id
 * @property int|null $dispatch_id
 * @property string|null $out_time
 * @property string|null $arrive_time
 * @property string|null $loc_time
 * @property string|null $liqv_time
 * @property string|null $ret_time
 * @property int|null $dispatched
 * @property string|null $departments
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment|null $department
 * @property-read \App\RoadtripPlan|null $road_trip_plan
 * @property-read \App\Ticket101 $ticket
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereDepartments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereDispatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereDispatched($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereLiqvTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereLocTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereRetTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FireDepartmentResult extends Model
{
    protected $fillable = [
        'ticket101_id',
        'fire_department_id',
        'accept_time',
        'out_time',
        'arrive_time',
        'loc_time',
        'liqv_time',
        'ret_time',
        'dispatched',
        'dispatch_id',
        'tech_id',
        'recommended',
    ];

    public function getOutTimeAttribute($value)
    {
        if($value != null){
            return Carbon::parse($value)->format('H:i');
        }
        return $value;
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function tech()
    {
        return $this->belongsTo(FormationTechItem::class, 'tech_id');
    }

    public function scopeIsDispatched($q, $search = true)
    {
        return $q->where('dispatched', $search);
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function road_trip_plan()
    {
        return $this->belongsTo(RoadtripPlan::class, 'dispatch_id');
    }

    public function getDepartment($dept, $departments)
    {
        return  $departments->where('departments', $dept)->first();
    }

    public function scopeRecommended($q, $search = true)
    {
        return $q->where('recommended', $search);
    }

}
