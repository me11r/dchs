<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'department_id',
        'name',
        'date_birth',
        'rank',
        'position',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'department_id');
    }

    public function statuses($status = null)
    {
        $arr = [
            'head_guards' => 'Нач. караула',
            'commander_squads' => 'Ком. отделения',
            'drivers' => 'Водитель',
            'privates' => 'Рядовой',
            'dispatchers' => 'Диспетчер',
        ];

        if($status != null){
            return $arr[$status] ?? null;
        }

        return $arr;
    }


}
