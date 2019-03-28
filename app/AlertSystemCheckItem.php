<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertSystemCheckItem extends Model
{
    protected $fillable = [
        'check1',
        'check2',
        'check3',
        'direction_id',
        'alert_system_check_id',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function alert_system_check()
    {
        return $this->belongsTo(AlertSystemCheck::class, 'alert_system_check_id');
    }


}
