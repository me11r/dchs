<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RoleToRight
 *
 * @property int $id
 * @property int $role_id
 * @property int $right_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleToRight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleToRight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleToRight whereRightId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleToRight whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleToRight whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RoleToRight extends Model
{
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
