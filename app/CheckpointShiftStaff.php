<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointShiftStaff extends BaseModel
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

    public $attributeNames = [
        'name' => 'ФИО',
        'guard_number_id' => 'Номер опергруппы',
        'position' => 'Должность',
        'city' => 'Город',
        'rank' => 'ранг',
        'military_rank' => 'военный ранг',
    ];

    public function shifts()
    {
        return $this->hasMany(CheckpointShiftStaffItem::class, 'staff_id');
    }
}
