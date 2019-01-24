<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DailyReportPerson
 */
class DailyReportPerson extends Model
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
}
