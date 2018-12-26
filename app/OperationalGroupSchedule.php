<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationalGroupSchedule extends Model
{
    protected $fillable = [
        'group_id',
        'date_begin',
        'date_end',
    ];

    public function group()
    {
        return $this->belongsTo(OperationalGroup::class, 'group_id');
    }

    public function scopeDate($q, $date)
    {
        return $q->where('date_begin', '<', $date)
            ->where('date_end', '>', $date);
    }

    public function scopeToday($q)
    {
        return $q->date(now());
    }
}
