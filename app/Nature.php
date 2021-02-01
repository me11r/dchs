<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nature extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'points' => 'array'
    ];
}
