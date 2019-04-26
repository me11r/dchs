<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Analytics101 extends Model
{
    protected $fillable = [
        'date',
    ];

    public function items()
    {
        return $this->hasMany(Analytics101Item::class, 'analytics_id');
    }

    public function getDateFromAttribute()
    {
        return $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null;
    }

    public function getDateToAttribute()
    {
        return $this->date ? Carbon::parse($this->date)->addDay(1)->format('d.m.Y') : null;
    }
}
