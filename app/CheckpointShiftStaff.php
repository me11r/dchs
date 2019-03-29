<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointShiftStaff extends Model
{
    use SoftDeletes;

    //ЛС Дежурная смена контрольно-пропускного режима Департамента
    protected $fillable = [
        'name',
        'guard_number_id',
        'position',
        'city',
        'rank',
        'military_rank',
    ];

    public function shifts()
    {
        return $this->hasMany(CheckpointShiftStaffItem::class, 'staff_id');
    }
}
