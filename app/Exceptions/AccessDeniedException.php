<?php

namespace App\Exceptions;


class AccessDeniedException extends \Exception
{
    public function __construct($message = 'Access Denied')
    {
        parent::__construct($message);
    }
}