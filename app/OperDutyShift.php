<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperDutyShift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'direction',
    ];

    public function items()
    {
        return $this->hasMany(OperDutyShiftStaffItem::class, 'shift_id');
    }
}
