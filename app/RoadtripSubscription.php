<?php

namespace App;

use App\Jobs\SendFcmMessages;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RoadtripSubscription
 *
 * @property int $id
 * @property string $token
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripSubscription whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoadtripSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class RoadtripSubscription extends Model
{
    protected $table = 'roadtrip_subscriptions';
    protected $fillable = ['user_id', 'token'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public static function forDepartment(int $deparment_id)
    {
        $users = User::whereFireDepartmentId($deparment_id)
            ->get(['id'])
            ->pluck('id')
            ->toArray();
        $model = (new static())->whereIn('user_id', $users)->get(['token'])->pluck('token')->toArray();
        return $model;
    }

    public static function notifyDepartment(int $department_id, int $tripId)
    {
        $tokens = self::forDepartment($department_id);
        if (!empty($tokens)) {
            dispatch(new SendFcmMessages($tokens,
                'Путевой лист',
                'Получен новый путевой лист!',
                url('/roadtrip/view/' . $tripId)
            ));
        };
    }
}
