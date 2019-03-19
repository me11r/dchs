<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket101HqRide extends Model
{
    protected $fillable = [
        'ticket101_id',
        'name',
        'department',
        'accept_time',
        'out_time',
        'arrive_time',
        'ret_time',
        'dispatch_time',
        'dispatched',
        'distance',
        'staff_count',
    ];

    public function getDeptNames()
    {
        return [
            'ДСПТ',
            'КШМ',
            'ИПЛ',
        ];
    }

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class,'ticket101_id');
    }
}
