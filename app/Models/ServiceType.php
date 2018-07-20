<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    public $table = 'service_types';

    public $timestamps = false;

    public $fillable = ['name'];
}
