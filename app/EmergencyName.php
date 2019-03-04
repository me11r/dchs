<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyName extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function card112()
    {
        return $this->belongsTo(\Card112::class,'emergency_name_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
