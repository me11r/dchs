<?php

namespace App\Models\Notification;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notification\NotificationGroup
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification\NotificationGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
