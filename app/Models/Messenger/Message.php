<?php
declare(strict_types=1);

namespace App\Models\Messenger;


final class Message extends \Eloquent
{
    protected $table = 'messenger_messages';
    protected $fillable = ['message', 'file_id', 'sender_id', 'reciever_id', 'is_viewed', 'message_type'];
}
