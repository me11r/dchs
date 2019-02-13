<?php


namespace App;


use App\Models\FormationPersonsItem;
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
 * @property int|null $is_approved
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FormationTechReport[] $tech_reports
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport approved($search = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport canEditReport()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationReport whereIsApproved($value)
 */
class FormationReport extends Model
{
    protected $table = 'formation_reports';
    protected $guarded = ['id'];

    public function scopeCanEditReport($q)
    {
        $created_date = $this->created_at;

        $time = Carbon::now();

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
    }

    public function tech_reports()
    {
        return $this->hasMany(FormationTechReport::class, 'form_id');
    }

    public function people_reports()
    {
        return $this->hasMany(FormationPersonsReport::class, 'form_id');
    }

    public function scopeApproved($q, $search = true)
    {
        return $q->where('is_approved', $search);
    }

    public function scopeSumTrainee($q, $types = [])
    {
        $reports = $this->people_reports;
        $data = [];
        if(!$types){
            $types = [
                'commander_squads',
                'head_guards',
                'drivers',
                'dispatchers',
                'privates',
            ];
        }
        foreach ($reports as $report) {
            foreach ($types as $type) {
                if(!isset($data[$type])){
                    $data[$type] = FormationPersonsItem::whereHas('report', function ($qq) use($report, $type){
                        $qq->where('report_id', $report->id)
                            ->where('trainee_type', $type);
                    })->count();
                }
                else{
                    $data[$type] += FormationPersonsItem::whereHas('report', function ($qq) use($report, $type){
                        $qq->where('report_id', $report->id)
                            ->where('trainee_type', $type);
                    })->count();
                }
            }

            $data['active'] = 0;
            $data['total'] = 0;

            foreach ($data as $key => $datum) {
                $data['active'] += $datum;
            }
        }

        return $data;
    }

    public function scopeSumGdzs($q)
    {
        return  $this->people_reports()->sum('asv'). '/'.$this->people_reports()->sum('dask');
    }
}