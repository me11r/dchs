<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FireDepartmentCheck extends Model
{
    protected $fillable = [
        'user',
        'fire_dept',
        'date',
        'note',
    ];
}
