<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        $users = User::whereFireDepartmentId($deparment_id)->get(['id']);
        return (new static())->where('user_id', 'in', $users)->get();
    }
}
