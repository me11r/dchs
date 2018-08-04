<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    public $table = 'weather';

    public $fillable = ['date', 'file'];
}
