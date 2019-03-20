<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'points' => 'array'
    ];
}
