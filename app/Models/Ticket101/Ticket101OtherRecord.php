<?php

namespace App\Models\Ticket101;

use Illuminate\Database\Eloquent\Model;

class Ticket101OtherRecord extends Model
{
    public $table = 'ticket101_other_records';

    public $fillable = [
        'ticket101_id',
        'time',
        'comment',
        'trunk_id',
        'count',
        'square'
    ];

    public $guarded = [
        'id'
    ];
}
