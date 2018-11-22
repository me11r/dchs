<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallInfo extends Model
{
    protected $fillable = [
        'count_112',
        'count_101',
        'count_109',
        'date',
    ];
}
