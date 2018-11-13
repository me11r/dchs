<?php

namespace App\Models\Notification;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notification\Notification
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string|null $send_date
 * @property string|null $receive_date
 * @property int $user_id
 * @property int $notification_status_id
 * @property int|null $notification_group_id
 * @property string|null $response
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Notification\NotificationGroup|null $group
 * @property-read \App\Models\Notification\NotificationStatus $status
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereNotificationGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereNotificationStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereReceiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereSendDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\Notification whereUserId($value)
 * @mixin \Eloquent
 */
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
