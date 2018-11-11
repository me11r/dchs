<?php

namespace App\Models\Notification;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $table = 'notifications';

    protected $fillable = [
        'title',
        'body',
        'send_date',
        'receive_date',
        'user_id',
        'notification_status_id',
        'notification_group_id',
        'response'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(NotificationStatus::class, 'notification_status_id');
    }

    public function group()
    {
        return $this->belongsTo(NotificationGroup::class, 'notification_group_id');
    }

}
