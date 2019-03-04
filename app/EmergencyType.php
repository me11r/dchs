<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
