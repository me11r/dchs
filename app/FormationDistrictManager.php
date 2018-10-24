<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormationDistrictManager extends Model
{
    protected $fillable = [
        'date'
    ];

    public function scopeDate($q, $search)
    {
        return $q->where('date', $search);
    }

    public function items()
    {
        return $this->hasMany(FormationDistrictManagerItem::class, 'report_id');
    }
}
