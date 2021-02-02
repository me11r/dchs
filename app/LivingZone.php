<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LivingZone extends Model
{
    protected $table = 'livingzones';
    protected $guarded = ['id'];

    protected $casts = [
        'points' => 'array'
    ];
}
