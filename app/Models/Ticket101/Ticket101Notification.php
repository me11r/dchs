<?php

namespace App\Models\Ticket101;

use Illuminate\Database\Eloquent\Model;

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
}
