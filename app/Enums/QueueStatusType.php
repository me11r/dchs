<?php

namespace App\Enums;


final class QueueStatusType
{
    public const CREATED = 'CREATED';
    public const QUEUED = 'QUEUED';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const ENDED = 'ENDED';
    public const ERROR = 'ERROR';
}