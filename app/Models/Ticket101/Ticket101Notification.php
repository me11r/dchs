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
 *
 * @mixin \Eloquent
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
