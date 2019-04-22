<?php


namespace App\Services;


use App\Models\Messenger\Message;
use App\PopupNotification;
use App\User;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    /**
     * @param int $senderId
     * @param int $receiverId
     * @param string $message
     * @return mixed
     */
    public function sendMessage(int $senderId, int $receiverId, string $message)
    {
        return Message::create([
            'message' => $message,
            'sender_id' => $senderId,
            'reciever_id' => $receiverId
        ]);
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @param string $message
     * @param string $url
     * @return mixed
     */
    public function sendPopupMessage(int $senderId, int $receiverId, string $message, string $url = '')
    {
        return PopupNotification::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'url' => $url,
            'is_viewed' => false,
        ]);
    }

    /**
     * @return User
     */
    public function getNotificationUser(): User
    {
        return Cache::rememberForever('cache_user_model', function () {
            return User::where('email', '=', 'notifications@localhost.net')->first();
        });
    }
}