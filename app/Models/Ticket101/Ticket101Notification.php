<?php

namespace App\Models\Ticket101;

use App\Models\Card112\Card112;
use App\Models\NotificationService;
use App\Models\ServiceType;
use App\Ticket101;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ticket101\Ticket101Notification
 *
 * @property int $id
 * @property int ticket101_id
 * @mixin \Eloquent
 * @property int|null $notification_service_id
 * @property string|null $message_time
 * @property string|null $name
 * @property string|null $arrive_time
 * @property int $checked
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ServiceType|null $service
 * @property-read \App\Ticket101 $ticket101
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereArriveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereMessageTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereNotificationServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereTicket101Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereUpdatedAt($value)
 * @property int|null $ticket112_id
 * @property-read \App\Models\Card112\Card112|null $ticket112
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Ticket101\Ticket101Notification whereTicket112Id($value)
 */
class Ticket101Notification extends Model
{
    public $table = 'ticket101_notifications';

    public $fillable = [
        'notification_service_id',
        'ticket101_id',
        'ticket112_id',
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
        return $this->belongsTo(ServiceType::class,'notification_service_id');
    }

    public function ticket101()
    {
        return $this->belongsTo(Ticket101::class, 'ticket101_id');
    }

    public function ticket112()
    {
        return $this->belongsTo(Card112::class, 'ticket112_id');
    }
}
