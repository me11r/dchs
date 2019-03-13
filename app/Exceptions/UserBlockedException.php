<?php

namespace App\Exceptions;


class UserBlockedException extends \Exception
{
    public function __construct($message = 'Пользователь заблокирован')
    {
        parent::__construct($message);
    }
}