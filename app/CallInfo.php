<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallInfo extends Model
{
    protected $fillable = [
        'count_112',
        'count_101',
        'count_109',
        'date',
        'count_102',
        'count_103',
        'count_info',
        'count_other',

        'count_emergency',

        'note',
    ];

    protected $appends = [
        'total_112',
        'total_101',
    ];

    public function getTotal112Attribute()
    {
        return $this->count_112 +
            $this->count_101 +
            $this->count_102 +
            $this->count_103 +
            $this->count_info +
            $this->count_other;
    }

    public function getTotal101Attribute()
    {
        return
            $this->count_101 +
            $this->count_emergency +
            $this->count_102 +
            $this->count_103 +
            $this->count_info +
            $this->count_other;
    }

    public function scopeGetSumByPeriod($q, $sumField, $dateFrom, $dateTo)
    {
        return $q->whereBetween('date', [$dateFrom, $dateTo])
            ->sum($sumField);
    }

    public function scopeShiftRecords($q, $hoursBegin = 7, $hoursEnd = 7)
    {
        $nowHours = now()->hour;

        //если текущее время от 00:00 до 07:00, берем интервал с 07:00 прошлого дня по 07:00 текущего

        //если текущее время от 07:00, интервал = 07:00 текущего дня по 07:00 следующего дня
        if($nowHours < 7) {

            $finalDateBegin = today()
                ->addDay(-1)
                ->addHours($hoursBegin)
                ->format('Y-m-d');

            $finalDateEnd = today()
                ->addHours($hoursEnd)
                ->format('Y-m-d');
        }
        else {

            $finalDateBegin = today()
                ->addHours($hoursBegin)
                ->format('Y-m-d');

            $finalDateEnd = today()
                ->addDay(1)
                ->addHours($hoursEnd)
                ->format('Y-m-d');
        }

        return $q->whereBetween('date', [$finalDateBegin, $finalDateEnd]);
    }
}
