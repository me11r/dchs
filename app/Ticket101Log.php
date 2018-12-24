<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket101Log extends Model
{
    protected $casts = [
        'body' => 'json'
    ];
    protected $fillable = [
        'user_id',
        'ticket101_id',
        'body',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
