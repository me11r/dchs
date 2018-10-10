<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirRescueReportPersonsItem extends Model
{
    protected $fillable = [
        'report_id',
        'staff_id',
        'status',
        'date_from',
        'date_to',
        'comment',
    ];

    public function scopeStatus($q, $search)
    {
        return $q->where('status', $search);
    }
}
