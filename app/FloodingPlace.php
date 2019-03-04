<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FloodingPlace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function cards112()
    {
        return $this->hasMany(\Card112::class, 'flooding_place_id');
    }
}
