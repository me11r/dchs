<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessengerRight extends Model
{
    protected $fillable = [
        'user_id',
        'can_send_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function can_send()
    {
        return $this->belongsTo(User::class, 'can_send_id');
    }
}
