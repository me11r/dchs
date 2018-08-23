<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencySituation extends Model
{
    public $table = 'emergency_situations';

    public $guarded = ['id'];
}
