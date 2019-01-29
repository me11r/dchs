<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card102RoadtripPlan extends Model
{
    protected $fillable = [
        'card102_id',
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
        'buran1' => 'Буран 1',
        'buran2' => 'Буран 2',
        'buran3' => 'Буран 3',
        'buran4' => 'Буран 4',
    ];
    public function ticket()
    {
        return $this->belongsTo(Card102::class, 'card102_id');
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
