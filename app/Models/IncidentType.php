<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    public $table = 'incident_types';

    public $timestamps = false;

    public $fillable = ['name'];
}
