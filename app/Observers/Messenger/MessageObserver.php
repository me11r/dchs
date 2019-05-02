<?php

namespace App\Observers\Messenger;

use App\Events\Messenger\MessageCreatedEvent;
use App\Models\Messenger\Message;

class MessageObserver
{
    public function created(Message $item)
    {
        event(new MessageCreatedEvent($item));
    }
}
