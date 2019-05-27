<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ServicePlanAdditional extends Model
{
    protected $fillable = [
        'date_time',
        'saved_animals',
        'poisoned',
        'wounded',
        'description',
        'location',
        'died',
        'injured',
        'hospitalized',
        'evacuated',
        'saved',
        'service_plan_id',
        'notification_101',
        'notification_112',
    ];

    public function getDateAttribute()
    {
        if($this->date_time) {
            return Carbon::parse($this->date_time)->format('Y-m-d');
        }

        return null;
    }

    public function getTimeAttribute()
    {
        if($this->date_time) {
            return Carbon::parse($this->date_time)->format('H:i');
        }

        return null;
    }

    public function getTimeHumanFormatAttribute()
    {
        if($this->time) {
            return Carbon::parse($this->time)->format('H') . ' час ' . Carbon::parse($this->time)->format('i') . ' мин.';
        }
        return null;
    }

    public function getDateHumanFormatAttribute()
    {
        if($this->date) {
            return Carbon::parse($this->date)->format('d.m.Y');
        }
        return null;
    }

    public function service_plan()
    {
        return $this->belongsTo(Ticket101ServicePlan::class, 'service_plan_id');
    }
}
