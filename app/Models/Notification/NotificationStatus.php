<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notification\NotificationStatus
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NotificationStatus extends Model
{
    public $table = 'notification_statuses';

    protected $fillable = [
        'name'
    ];

}
