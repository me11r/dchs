<?php

namespace App\Models\Notification;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NotificationGroup extends Model
{
    public $table = 'notification_groups';

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'notification_group_users',
            'notification_group_id'
        );
    }

}
