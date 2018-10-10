<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    }

    public function hasRight($right)
    {
        if(is_array($right)){
            $hasRight = $this->rights()->whereIn('right_id', $right)->exists();
        }
        else{
            $hasRight = $this->rights()->where('right_id', $right)->exists();
        }
        return $hasRight ? true : false;

    }
}

