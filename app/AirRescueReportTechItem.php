<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AirRescueReportTechItem
 *
 * @property int $id
 * @property int $aircraft_id
 * @property int $report_id
 * @property string $status
 * @property string|null $reserve
 * @property string|null $date_from
 * @property string|null $date_to
 * @property string|null $comment
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Aircraft $aircraft
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem status($search)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereAircraftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AirRescueReportTechItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AirRescueReportTechItem extends Model
{
    protected $fillable = [
        'aircraft_id',
        'report_id',
        'status',
        'reserve',
        'date_from',
        'date_to',
        'comment',
        'simplex',
        'vsu3',
        'vsu5',
        'vsu10',
        'winch',
        'sur',
        'external_suspension',
    ];

    public function report()
    {
        return $this->belongsTo(AirRescueReport::class, 'report_id');
    }

    public function scopeStatus($q, $search = 'action')
    {
        return $q->where('status', $search);
    }

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function additionalInfo()
    {
        $result = '';
        if($this->simplex){
            $result .= 'SIMPLEX, ';
        }

        if($this->vsu3){
            $result .= 'ВСУ 3, ';
        }

        if($this->vsu5){
            $result .= 'ВСУ 5, ';
        }

        if($this->vsu10){
            $result .= 'ВСУ 10, ';
        }

        if($this->winch){
            $result .= 'лебедка, ';
        }

        if($this->sur){
            $result .= 'СУР, ';
        }

        if($this->external_suspension){
            $result .= 'Внешняя подвеска (до 5т), ';
        }

        return $result;
    }
}
