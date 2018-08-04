<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Repositories\Contracts\ChatInterface;

class EloquentChatRepository extends Repository implements ChatInterface
{

    public function model()
    {
        return Chat::class;
    }

}