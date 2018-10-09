<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aircraft extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'number',
        'type', // airplane|helicopter
        'aircraft_type_id', // airplane|helicopter
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function scopeType($q, $search)
    {
        return $q->where('type', $search);
    }

    public function scopeNumber($q, $search)
    {
        return $q->where('number', $search);
    }

    public function aircraft_type()
    {
        return $this->belongsTo(AircraftType::class, 'aircraft_type_id');
    }
}
