<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectClassification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];
}