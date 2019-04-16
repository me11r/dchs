<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $searchByDate = 'created_at';

    public $attributeNames = [];

    public function scopeSkipNullValue($q, $field, $search)
    {
        return $search ? $q->where($field, $search) : $q;
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
                ->format('Y-m-d H:i:s');

            $finalDateEnd = today()
                ->addHours($hoursEnd)
                ->format('Y-m-d H:i:s');
        }
        else {

            $finalDateBegin = today()
                ->addHours($hoursBegin)
                ->format('Y-m-d H:i:s');

            $finalDateEnd = today()
                ->addDay(1)
                ->addHours($hoursEnd)
                ->format('Y-m-d H:i:s');
        }

        return $q->whereBetween($this->searchByDate, [$finalDateBegin, $finalDateEnd]);
    }
}
