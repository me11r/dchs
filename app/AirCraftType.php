<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirCraftType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function aircrafts()
    {
        return $this->hasMany(Aircraft::class, 'aircraft_type_id');
    }
}
