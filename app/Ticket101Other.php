<?php

namespace App;

use App\Models\BaseModel;
use App\Models\FireDepartmentResult;
use App\Models\Staff;
use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Ticket101Other
 *
 * @property int $id
 * @property int $ticket_101_id
 * @property int $fire_department_id
 * @property int|null $department
 * @property string|null $time_begin
 * @property int $ride_type_id
 * @property string|null $object_name
 * @property int $staff_id
 * @property string|null $time_end
 * @property string|null $note
 * @property string|null $direction
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\FireDepartment $fire_department
 * @property-read \App\RideType $ride_type
 * @property-read \App\Models\Staff $staff
 * @property-read \App\Ticket101 $ticket101
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereFireDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereObjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereRideTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTimeBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket101Other whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket101Other extends BaseModel
{
    protected $searchByDate = 'custom_created_at';

    protected $fillable = [
        'ride_type_id',
        'time_begin',
        'time_end',
        'object_name',
        'note',
        'formation_report_id',
        'responsible_person',
        'direction',
        'final_ride_type_id',
        'final_responsible_person',
        'final_direction',
        'final_object_name',
        'custom_created_at', //клон created_at, можно править в карточке
        'created_by',
        'changed_by',
        'delayed_at',
        'imported_at',
        'fire_department_id',
        'move',
        'area_id',
    ];

    protected $appends = [
        'delayed',
    ];
    
    public function city_area()
    {
        return $this->hasOne(CityArea::class, 'id', 'area_id');
    }
    
    public function ride_type()
    {
        return $this->belongsTo(RideType::class,'ride_type_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class,'fire_department_id');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function changed_by_user()
    {
        return $this->belongsTo(User::class,'changed_by');
    }

    public function formation_report()
    {
        return $this->belongsTo(FormationReport::class,'formation_report_id');
    }

    public function results()
    {
        return $this->hasMany(FireDepartmentResult::class, 'ticket101_other_id');
    }

    public function roadtrip_plans()
    {
        return $this->hasMany(RoadtripPlan::class, 'card101_other_id');
    }

    public function hqRides()
    {
        return $this->hasMany(Ticket101OtherHqRide::class, 'ticket101_id');
    }

    public function scopeImported($q, $search = null)
    {
        return $search ? $q->where('imported_at', $search) : $q->whereNotNull('imported_at');
    }

    public function setFinalRideTypeIdAttribute($value)
    {
        if(!$value) {
            $this->attributes['final_ride_type_id'] = $this->attributes['ride_type_id'];
        }
        else {
            $this->attributes['final_ride_type_id'] = $value;
        }
    }

    public function setFinalResponsiblePersonAttribute($value)
    {
        if(!$value) {
            $this->attributes['final_responsible_person'] = $this->attributes['responsible_person'];
        }
        else {
            $this->attributes['final_responsible_person'] = $value;
        }
    }

    public function setFinalDirectionAttribute($value)
    {
        if(!$value) {
            $this->attributes['final_direction'] = $this->attributes['direction'];
        }
        else {
            $this->attributes['final_direction'] = $value;
        }
    }

    public function setFinalObjectNameAttribute($value)
    {
        if(!$value) {
            $this->attributes['final_object_name'] = $this->attributes['object_name'];
        }
        else {
            $this->attributes['final_object_name'] = $value;
        }
    }

    //attribute: date
    public function getDateAttribute()
    {
        $format = 'd.m.Y';
        if ($this->custom_created_at) {
            return Carbon::parse($this->custom_created_at)->format($format);
        }
        if ($this->created_at) {
            return $this->created_at->format($format);
        }

        return null;
    }

    //attribute: delayed
    public function getDelayedAttribute()
    {
        return $this->delayed_at ? true : false;
    }

    //attribute: dispatched_fds
    public function getDispatchedFdsAttribute()
    {
        $results = $this->results()->whereNotNull('dispatch_id')->get()->map(function ($q) {
            return ['department' => $q->department->title ?? null];
        })->unique();

        $results_hq = $this->hqRides()->whereNotNull('dispatched')->get()->map(function ($q) {
            return ['department' => $q->name];
        });

        if($results_hq->count() && $results->count()) {
            $results = $results->merge($results_hq);
        }
        elseif ($results_hq->count()) {
            $results = $results_hq;
        }

        $fd = '';

        foreach ($results as $key => $result) {
            $fd .= $result['department'];
            $fd .= ++$key !== count($results) ? ', ' : '';
        }

        return $fd;
    }

    public function setCustomCreatedAtAttribute($value)
    {
        if(!$value) {
            $value = now();
        }
        $this->attributes['custom_created_at'] = $value;
    }
}
