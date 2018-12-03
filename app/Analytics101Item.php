<?php

namespace App;

use App\Dictionary\FireObject;
use App\Dictionary\TripResult;
use Illuminate\Database\Eloquent\Model;

class Analytics101Item extends Model
{
    protected $fillable = [
        'analytics_id',
        'text',
        'trip_result_id',
        'ticket101_id',
    ];

    public function analytics()
    {
        return $this->belongsTo(Analytics101::class, 'analytics_id');
    }

    public function trip_result()
    {
        return $this->belongsTo(TripResult::class, 'trip_result_id');
    }

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }
}
