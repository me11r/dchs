<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nickname extends Model
{
    public $table = 'nicknames';

    public $fillable = ['name'];
}
