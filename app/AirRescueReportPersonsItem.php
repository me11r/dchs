<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

/**
 * App\AirRescueReportPersonsItem
 *
 * @property int $id
 * @property int $report_id
 * @property int $staff_id
 * @property string $status
 * @property string|null $date_from
 * @property string|null $date_to
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Staff $staff
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem status($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportPersonsItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AirRescueReportPersonsItem extends Model
{
    protected $fillable = [
        'report_id',
        'staff_id',
        'status',
        'date_from',
        'date_to',
        'comment',
    ];

    public function scopeStatus($q, $search)
    {
        return $q->where('status', $search);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
