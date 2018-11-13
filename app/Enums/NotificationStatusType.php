<?php
/**
 * Created by PhpStorm.
 * User: nbah1990
 * Date: 08-Nov-18
 * Time: 09:27
 */

namespace App\Enums;


class NotificationStatusType
{
    public const CREATED = 1;
    public const SENT = 2;
    public const DELIVERED = 3;
    public const ERROR = 4;
    public const TOKEN_NOT_FOUND = 5;
}
