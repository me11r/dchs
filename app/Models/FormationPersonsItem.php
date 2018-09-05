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

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }
}
