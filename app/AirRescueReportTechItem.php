<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirRescueReportTechItem extends Model
{
    protected $fillable = [
        'aircraft_id',
        'report_id',
        'status',
        'reserve',
        'date_from',
        'date_to',
        'comment',
    ];

    public function scopeStatus($q, $search)
    {
        return $q->where('status', $search);
    }
}
