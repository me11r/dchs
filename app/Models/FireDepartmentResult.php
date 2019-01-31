<?php

namespace App\Models;

use App\Chronology101;
use App\FireDepartment;
use App\NormPsp;
use App\RoadtripPlan;
use App\Ticket101;
use App\Ticket101Other;
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
 * @property int|null $recommended
 * @property int|null $tech_id
 * @property string|null $accept_time
 * @property-read \App\Models\FormationTechItem|null $tech
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult isDispatched($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult recommended($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereAcceptTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereRecommended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereTechId($value)
 * @property int|null $get_back
 * @property string|null $dispatch_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult arrived($ticket_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult markToGetBack()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult onWay($ticket_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereDispatchTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FireDepartmentResult whereGetBack($value)
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
        'dispatch_time',
        'tech_id',
        'recommended',
        'get_back',
        'promoted_at',
        'promoted_department',
        'ticket101_other_id',
    ];

    protected $appends = [
        'status',
    ];

    public function chronology()
    {
        return $this->hasMany(Chronology101::class, 'fire_department_result_id');
    }

    public function scopeOnWay($q, $ticket_id)
    {
        return $q->where('ticket101_id', $ticket_id)
            ->whereNotNull('out_time');
    }

    public function scopeMarkToGetBack($q)
    {
        return $q->update(['get_back' => true, 'updated_at' => now()]);
    }

    public function scopeArrived($q, $ticket_id)
    {
        return $q->where('ticket101_id', $ticket_id)
            ->whereNotNull('arrive_time')
            ->whereNull('ret_time');
    }

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

    public function ticket_other()
    {
        return $this->belongsTo(Ticket101Other::class, 'ticket101_other_id');
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

    public function getDuration()
    {
        $outTime = $this->out_time ? Carbon::parse($this->out_time) : null;
        $retTime = $this->ret_time ? Carbon::parse($this->ret_time) : null;

        if ($outTime && $retTime){
            return gmdate('H:i:s', $outTime->diffInSeconds($retTime));
        }

        return 'Нет результата';
    }

    public function getStatusAttribute()
    {
        $resultStatus = "ПЧ";

        //проверка в общей таблице выездов
        $deptOnRide = FireDepartmentResult::where('tech_id', $this->tech_id)
            ->where('id', '<>', $this->id)
            ->latest()
            ->first();

        //проверка в таблице нормативы по ПСП
        if($this->tech) {
            $norm = NormPsp::whereNull('time_end')
                ->whereNotNull('time_begin')
                ->where('fire_department_id', $this->fire_department_id)
                ->where('department', $this->tech->department)
                ->first();

            if($norm) {
                return "Нормативы по ПСП";
            }
        }

        if(!$deptOnRide || $deptOnRide->ret_time !== null || $deptOnRide->dispatch_time === null) {
            return $resultStatus;
        }

        return $deptOnRide->ticket_other ? ($deptOnRide->ticket_other->ride_type->name ?? null) : null;

    }

}
