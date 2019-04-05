<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlertSystemCheckItem extends Model
{
    protected $fillable = [
        'check1',
        'check2',
        'check3',
        'note',
        'direction_id',
        'alert_system_check_id',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function getTitle($val)
    {
        if ($val === 1) {
            return 'Исправен';
        }

        if ($val === 0) {
            return 'Не исправен';
        }

        return null;
    }

    public function alert_system_check()
    {
        return $this->belongsTo(AlertSystemCheck::class, 'alert_system_check_id');
    }

    public function getCheck1TitleAttribute()
    {
        return $this->getTitle($this->check1);
    }

    public function getCheck2TitleAttribute()
    {
        return $this->getTitle($this->check2);
    }

    public function getCheck3TitleAttribute()
    {
        return $this->getTitle($this->check3);
    }


}
