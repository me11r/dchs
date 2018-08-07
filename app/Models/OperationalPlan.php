<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalPlan extends Model
{
    public $table = 'dict_operational_plan';

    public $fillable = ['name'];
}
