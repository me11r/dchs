<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PopupNotification extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'url',
        'is_viewed',
        'is_permanent',
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

    public function scopeSetViewed($q, $condition)
    {
        return $q->where('id', $condition)
            ->orWhere('url', $condition)
            ->update(['is_viewed' => true]);
    }

    /*отправка сообщений в зависимости от прав пользователя*/
    public function scopeSendAccordingToRight($qq, $messageData, $rights, $except = [])
    {
        $users = User::whereHas('role.rights', function ($q) use ($rights, $except) {
            if (is_array($rights)) {
                $q->whereIn('name', $rights);
            }
            else {
                $q->where('name', $rights);
            }

            if(count($except)) {
                $q->whereNotIn('name', $except);
            }
        });

        $users->each(function ($user) use ($messageData) {
            $user->popup_notifications_received()->create([
                'sender_id' => $messageData['sender_id'] ?? Auth::id(),
                'message' => $messageData['message'],
                'url' => $messageData['url'],
                'is_viewed' => false,
                'is_permanent' => false,
            ]);
        });

        return $users;
    }
}
