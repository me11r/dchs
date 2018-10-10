<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormationPersonsItem extends Model
{
    protected $fillable = [
        'staff_id',
        'report_id',
        'status',
        'rank',
        'comment',
        'date_from',
        'date_to',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }

    public function scopeGetStat($q, $staff_id, $date_begin, $date_end, $status = 'active')
    {
        return $q->where('staff_id', $staff_id)
            ->where('status', $status)
            ->whereBetween('updated_at',[$date_begin, $date_end]);
    }
}
