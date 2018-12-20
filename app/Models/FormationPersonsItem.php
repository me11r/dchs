<?php

namespace App\Models;

use App\FormationPersonsReport;
use App\GuardNumber;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FormationPersonsItem
 *
 * @property int $id
 * @property int $staff_id
 * @property int $report_id
 * @property string|null $status
 * @property string|null $rank
 * @property string|null $date_to
 * @property string|null $date_from
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Staff $staff
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem getStat($staff_id, $date_begin, $date_end, $status = 'active')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem rank($rank)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FormationPersonsItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationPersonsItem extends Model
{
    protected $fillable = [
        'staff_id',
        'report_id',
        'status',
        'rank',
        'comment',
        'date_from',
        'date_to',
        'guard_number_id',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function guard_number()
    {
        return $this->belongsTo(GuardNumber::class, 'guard_number_id');
    }

    public function report()
    {
        return $this->belongsTo(FormationPersonsReport::class, 'report_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }

    public function scopeGetStat($q, $staff_id, $date_begin, $date_end, $status = 'active')
    {
        return $q->where('staff_id', $staff_id)
            ->where('status', $status)
            ->whereBetween('updated_at',[$date_begin, $date_end]);
    }

    public function scopeByRankAndForm($q, $rank, $form_id)
    {
        return $q->whereHas('report', function ($q) use ($form_id){
            $q->where('form_id', $form_id);
        })->where('rank', $rank);
    }
}
