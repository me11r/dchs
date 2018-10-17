<?php

namespace App;

use App\Models\Staff;
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

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
