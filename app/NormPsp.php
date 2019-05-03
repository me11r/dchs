<?php

namespace App;

use Carbon\Carbon;
use function foo\func;
use Illuminate\Database\Eloquent\Model;

class NormPsp extends Model
{
    protected $fillable = [
        'date',
        'time_begin',
        'time_end',
        'fire_department_id',
        'norm_number_id',
        'norm_type_id',
        'responsible_person',
        'note',
        'imported_at',
        'gdzs_included_30', //с включение гдзс (30 минут)
    ];

    public function norm_type()
    {
        return $this->belongsTo(NormType::class, 'norm_type_id');
    }

    public function norm_number()
    {
        return $this->belongsTo(NormNumber::class, 'norm_number_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }

    public function departments()
    {
        return $this->hasMany(NormPspDepartment::class,'norm_id');
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

        return $q
            ->where(function ($q1) use ($finalDateBegin, $finalDateEnd) {
                $q1->whereBetween('date', [$finalDateBegin, $finalDateEnd]);
            });
    }
}
