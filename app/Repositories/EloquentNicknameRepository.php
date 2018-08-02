<?php

namespace App\Repositories;

use App\Models\Nickname;
use App\Repositories\Contracts\NicknameInterface;

class EloquentNicknameRepository extends Repository implements NicknameInterface
{

    public function model()
    {
        return Nickname::class;
    }

}