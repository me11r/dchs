<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AlertSystemCheck extends Model
{
    protected $fillable = [
        'date'
    ];

    public function items()
    {
        return $this->hasMany(AlertSystemCheckItem::class, 'alert_system_check_id');
    }

    public function scopeDate($q, $search)
    {
        if(is_array($search)) {
            return $q->whereBetween('date', $search);
        }

        return $q->where('date', $search);
    }

    //date_human_format
    public function getDateHumanFormatAttribute()
    {
        return $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null;
    }


}
