<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DutyPersonsService extends Model
{
    protected $fillable = [
        'date',
        'police_dept102',
        'ambulance103',
        'gas_service104',
    ];
}
