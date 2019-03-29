<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckpointShiftStaffReport extends Model
{
    protected $fillable = [
        'note',
        'date',
        'shift_id',
    ];

    public function items()
    {
        return $this->hasMany(CheckpointShiftStaffItem::class, 'report_id');
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
