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
        'retreat_time',
        'arrive_time',
        'ret_time',
        'dispatch_time',
        'dispatched',
        'distance',
        'staff_count',
    ];

    protected $appends = [
        'retreated'
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

    public function getRetreatedAttribute()
    {
        if($this->retreat_time && $this->retreat_time !== '00:00') {
            return true;
        }

        return false;
    }
}
