<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationMedicalReport
 *
 * @property int $id
 * @property string $report_date
 * @property string|null $manager
 * @property int $staffed
 * @property int $on_duty
 * @property string|null $formation
 * @property int $people
 * @property int $tech
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereFormation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereOnDuty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport wherePeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereStaffed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereTech($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMedicalReport todayRecords()
 */
class FormationMedicalReport extends Model
{
    protected $table = 'formation_medical_report';
    protected $guarded = ['id'];

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('report_date', date('Y-m-d'));
    }
}