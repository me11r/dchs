<?php

namespace App;

use App\Exceptions\AccessDeniedException;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Right[] $rights
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $device_token
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $last_login
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @property int|null $fire_department_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFireDepartmentId($value)
 * @property-read \App\FireDepartment $department
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login',
        'fire_department_id',
        'role_id',
        'device_token',
        'service_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*    public function rights_()
        {
            return $this->hasManyThrough(\App\Right::class, \App\User\Right::class, 'right_id', 'id', 'id');
        }*/

    public function rights()
    {
        return $this->belongsToMany(\App\Right::class, 'user_rights');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function scopeIsAdmin($q)
    {
        return $q->isRole('admin');
    }

    public function scopeIsRole($q, $role)
    {
        $user = Auth::user();
        if($user && $user->role){
            if($user->role->name === $role){
                return true;
            }
        }

        return false;
    }

    public function scopeAnyRole($q, $role)
    {
        $user = Auth::user();
        if($user && $user->role){
            if(is_array($role)){
                $query = $user->role()->whereIn('name', $role)->exists();
            }
            else{
                $query = $user->role()->where('name', $role)->exists();
            }

            return $query;
        }

        return false;
    }

    public function hasRight($right_id)
    {
        /** @var array $rights */
//        $rights = $this->rights->pluck('id')->toArray();
//        return in_array($right_id, $rights, false);

        if(!$this->role){
            return false;
        }

        return $this->role->hasRight($right_id);
    }

    public function hasAnyRight($rights_ids)
    {
        /** @var array $rights */
        $rights = $this->rights->pluck('id')->toArray();
        return \count(array_intersect($rights, $rights_ids)) > 0;
    }

    public function hasAllRights($rights_ids)
    {
        /** @var array $rights */
        $rights = $this->rights->pluck('id')->toArray();
        return \count(array_intersect($rights, $rights_ids)) === count($rights);
    }

    public static function checkDepartment($dept)
    {
        $user = Auth::user();

        if(($user && $user->id == 1) || ($user && !$user->fire_department_id)){
            return true;
        }

        if (!isset($user) || ($user->fire_department_id != $dept)) {
//            throw new AccessDeniedException();
            false;
        }

        return true;
    }

    public function isBlocked(): bool
    {
        return $this->hasRight(1) !== true;
    }

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id', 'id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'department_id', 'fire_department_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'fire_department_id', 'fire_department_id');
    }

    public function currentRole()
    {
        try{
            return Auth::user()->role->name;
        }
        catch (\Exception $e){
            return null;
        }
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    /*public function canRole($role = null)
    {
        $roles = [];

        if($this->currentRole() == 'admin'){
            $roles = [
                'admin',
                'user',
                'fss-admin',
                'logs',
            ];
        }
        elseif($this->currentRole() == 'user'){
            $roles = [
                'user',
            ];
        }
        elseif($this->currentRole() == 'fss-admin'){
            $roles = [
                'user',
                'fss-admin',
                'logs',
            ];
        }
        elseif($this->currentRole() == 'logs'){
            $roles = [
                'logs',
            ];
        }

        if($role){
            return in_array($role, $roles);
        }

        return $roles;
    }*/

}
