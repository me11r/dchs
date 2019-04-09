<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrillType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket101::class, 'drill_type_id');
    }
}
