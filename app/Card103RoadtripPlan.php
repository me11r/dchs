<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card103RoadtripPlan extends Model
{
    protected $fillable = [
        'card103_id',
        'department_id',
        'dispatch_time',
        'accept_time',
        'out_time',
        'arrive_time',
        'loc_time',
        'liqv_time',
        'ret_time',
        'dispatched',
    ];

    protected $appends = [
        'department_title',
    ];

    public $deptsList = [
        'linear' => 'Линейное',
        'special' => 'Специализированное',
        'neonatal' => 'Неонатальное',
        'gynecological' => 'Акушерско-гинекологическое',
    ];
    public function ticket()
    {
        return $this->belongsTo(Card103::class, 'card103_id');
    }

    public function getDepartmentTitleAttribute()
    {
        $currentDeptName = $this->department_id;
        if (isset($this->deptsList[$currentDeptName])) {
            return $this->deptsList[$currentDeptName];
        }

        return '';
    }
}
