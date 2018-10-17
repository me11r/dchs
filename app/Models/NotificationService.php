<?php

namespace App\Models;

use App\User;
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

    public function headUser()
    {
        return $this->hasOne(User::class, 'id', 'head_user_id');
    }
}
