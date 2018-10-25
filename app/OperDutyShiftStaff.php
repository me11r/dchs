<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperDutyShiftStaff extends Model
{
    protected $fillable = [
        'name',
    ];

    public function shifts()
    {
        return $this->hasMany(OperDutyShiftStaffItem::class, 'staff_id');
    }
}
