<?php

namespace App;

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
}
