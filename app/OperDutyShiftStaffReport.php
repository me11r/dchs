<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperDutyShiftStaffReport extends Model
{
    protected $fillable = [
        'note',
        'date',
        'shift_id',
    ];

    public function items()
    {
        return $this->hasMany(OperDutyShiftStaffItem::class, 'report_id');
    }

    public function shift()
    {
        return $this->belongsTo(OperDutyShift::class, 'shift_id');
    }

    public function scopeDate($q, $rank)
    {
        return $q->where('date', $rank);
    }
}
