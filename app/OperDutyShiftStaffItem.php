<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

/**
 * App\OperDutyShiftStaffItem
 *
 * @property int $id
 * @property int $shift_id
 * @property int $staff_id
 * @property string|null $rank
 * @property string|null $date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $rank_human_format
 * @property-read \App\OperDutyShift $shift
 * @property-read \App\OperDutyShiftStaff $staff
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem date($rank)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem rank($rank)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OperDutyShiftStaffItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OperDutyShiftStaffItem extends Model
{
    protected $fillable = [
        'shift_id',
        'staff_id',
        'rank',
        'date',
    ];

    private $ranks = [
        'duty_officer' => 'Оперативный дежурный',
        'duty_officer_assistant' => 'Помощник ОД',
    ];

    public function staff()
    {
        return $this->belongsTo(OperDutyShiftStaff::class, 'staff_id');
    }

    public function shift()
    {
        return $this->belongsTo(OperDutyShift::class, 'shift_id');
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
}
