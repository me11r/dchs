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
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }
}
