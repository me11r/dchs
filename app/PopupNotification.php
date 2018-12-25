<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PopupNotification extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'url',
        'is_viewed',
        'popup_position',
        'popup_type',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
