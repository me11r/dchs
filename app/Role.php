<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Right[] $rights
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role name($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'title',
    ];

    private $roles = [
        [
            'name' => 'admin',
            'title' => 'Администратор',
        ],
        [
            'name' => 'dispatcher_od',
            'title' => 'Диспетчер ОД',
        ],
        [
            'name' => 'dispatcher_pch',
            'title' => 'Диспетчер ПЧ',
        ],
        [
            'name' => 'dstp',
            'title' => 'ДСТП',
        ],
        [
            'name' => 'emergency_service',
            'title' => 'Служба взаимодействия',
        ],
        [
            'name' => 'analyst',
            'title' => 'Аналитик',
        ],
        [
            'name' => 'head',
            'title' => 'Руководство',
        ],
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function rights()
    {
        return $this->belongsToMany(Right::class, 'role_to_rights', 'role_id');
            /*->withPivot('right_id');*/
    }

    public function hasRight($right)
    {
        if(is_array($right)){
            $hasRight = $this->rights()
                ->whereIn('right_id', $right)
                ->exists();
        }
        else{
            if(!is_numeric($right)){
                $right = Right::where('title', $right)->first();
                if($right){
                    $right = $right->id;
                }
            }
            $hasRight = $this->rights()
                ->where('right_id', $right)
                ->exists();

        }
        return $hasRight ? true : false;
    }
}

