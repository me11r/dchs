<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tech extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'points' => 'array'
    ];
}
