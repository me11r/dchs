<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CheckpointShiftStaffItem extends Model
{
    protected $fillable = [
        'staff_id',
        'rank',
        'date',
        'inactive_type',
        'date_from',
        'date_to',
        'comment',
        'report_id',
    ];

    private $ranks = [
        'duty_officer' => 'Оперативный дежурный',
        'duty_officer_assistant' => 'Помощник ОД',
    ];

    public $inactiveTypes = [
        'vacation' => 'Отпуск',
        'business_trip' => 'Командировка',
        'maternity' => 'Декрет',
        'sick_leave' => 'Больничный',
    ];

    public function staff()
    {
        return $this->belongsTo(CheckpointShiftStaff::class, 'staff_id');
    }

    public function report()
    {
        return $this->belongsTo(CheckpointShiftStaffReport::class, 'report_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }

    public function scopeDate($q, $rank)
    {
        return $q->where('date', $rank);
    }

    public function getRankHumanFormatAttribute()
    {
        return $this->ranks[$this->rank] ?? '';
    }

    public function scopeGetStat($q, $manager_id, $date_begin, $date_end, $inactive_type)
    {
        return $q->where('staff_id', $manager_id)
            ->where(function ($qq) use ($inactive_type) {
                if($inactive_type === null) {
                    $qq->whereNull('inactive_type');
                }
                else {
                    $qq->whereNotNull('inactive_type');
                }
            })
            ->whereBetween('date',[$date_begin, $date_end]);
    }

    //inactive_staff_info
    public function getInactiveStaffInfoAttribute()
    {
        if($this->inactive_type && $this->staff) {

            $dateFrom = $this->date_from ? Carbon::parse($this->date_from)->format('d-m-Y') : null;
            $dateTo = $this->date_to ? Carbon::parse($this->date_to)->format('d-m-Y') : null;

            $str = '';
            $str .= "{$this->staff->name} ";
            $str .= $this->inactiveTypes[$this->inactive_type] ?? null;
            $str .= ' ';
            $str .= $dateFrom ? "c {$dateFrom} " : "";
            $str .= $dateTo ? "по {$dateTo} " : "";
            $str .= $this->comment;

            return $str;
        }

        return null;
    }
}
