<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationReport
 *
 * @property int $id
 * @property string $report_date
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationReport extends Model
{
    protected $table = 'formation_reports';
    protected $guarded = ['id'];

    public function scopeCanEditReport($q)
    {
        $created_date = $this->created_at;
//        if($created_date == null){
//            return true;
//        }

        $time = Carbon::now();
//        $some_date = Carbon::create($time->year, $time->month, $time->day - 1);

        $morning_begin = Carbon::create($time->year, $time->month, $time->day, 8, 0, 0); //set time to 08:00
        $morning_end = Carbon::create($time->year, $time->month, $time->day, 9, 0, 0); //set time to 09:00

        $evening_begin = Carbon::create($time->year, $time->month, $time->day, 18, 0, 0); //set time to 18:00
        $evening_end = Carbon::create($time->year, $time->month, $time->day, 19, 0, 0); //set time to 19:00

        if($created_date->isYesterday()){
            $is_between = $time->between($morning_begin, $morning_end, true);
        }
        elseif($created_date->isToday()){
            $is_between = $time->between($evening_begin, $evening_end, true);
        }

        return $is_between ?? false;







        $is_yesterday = $created_date->isYesterday();
        $is_today = $created_date->isToday();
        $pass_after_9 = now()->format('H') > 9;

        if(!$is_yesterday && !$is_today){
            return false;
        }
        elseif($is_today && !$pass_after_9){
            return true;
        }
        elseif($is_today){
            return true;
        }
        elseif($is_yesterday && !$pass_after_9){
            return false;
        }

        return false;
    }
}