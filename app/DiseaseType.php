<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiseaseType extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];
}
