<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DailyReportPerson
 */
class DailyReportPerson extends BaseModel
{
    public $table = 'daily_report_persons';

    public $fillable = [
        'position',
        'city',
        'post',
        'name',
        'type',
        'report_type',
    ];

    public $attributeNames = [
        'position' => 'Кому',
        'city' => 'Город',
        'post' => 'Должность',
        'name' => 'ФИО',
        'type' => 'Тип (расположение)',
        'report_type' => 'Тип отчета',
    ];
}
