<?php

namespace App\Repositories;

use App\Models\Message;
use App\Repositories\Contracts\MessageInterface;

class EloquentMessageRepository extends Repository implements MessageInterface
{

    public function model()
    {
        return Message::class;
    }

}