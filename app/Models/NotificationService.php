<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationService
 *
 * @property int $id
 *
 * @package App\Models
 */
class NotificationService extends Model
{
    public $table = 'notification_services';

    public $fillable = [
        'name',
        'code',
        'head_user_id'
    ];
}
