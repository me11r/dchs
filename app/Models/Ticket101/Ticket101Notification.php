<?php

namespace App\Models\Ticket101;

use App\Models\NotificationService;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ticket101\Ticket101Notification
 *
 * @property int $id
 *
 * @mixin \Eloquent
 */
class Ticket101Notification extends Model
{
    public $table = 'ticket101_notifications';

    public $fillable = [
        'notification_service_id',
        'ticket101_id',
        'message_time',
        'name',
        'arrive_time',
        'checked'
    ];

    public $guarded = [
        'id'
    ];

    public function service()
    {
        return $this->hasOne(NotificationService::class, 'id', 'notification_service_id');
    }
}
