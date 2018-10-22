<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class OperDutyShiftStaffItem extends Model
{
    protected $fillable = [
        'shift_id',
        'staff_id',
        'rank',
        'date',
    ];

    private $ranks = [
        'duty_officer' => 'Оперативный дежурный',
        'duty_officer_assistant' => 'Помощник ОД',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function shift()
    {
        return $this->belongsTo(OperDutyShift::class, 'shift_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }

    public function scopeDate($q, $rank)
    {
        return $q->where('date', $rank);
    }

    public function getRankHumanFormatAttribute()
    {
        return $this->ranks[$this->rank] ?? '';
    }
}
