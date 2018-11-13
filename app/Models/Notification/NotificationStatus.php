<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    public $table = 'notification_statuses';

    protected $fillable = [
        'name'
    ];

}
