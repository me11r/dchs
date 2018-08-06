<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quake extends Model
{
    public $table = 'quakes';

    public $fillable = [
        'description',
        'date_almaty',
        'date_greenwich',
        'epicenter',
        'energy_class',
        'mpv',
        'deep',
        'coordinates',
        'information'
    ];
}
