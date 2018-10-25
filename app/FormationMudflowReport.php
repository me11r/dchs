<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\FormationMudflowReport
 *
 * @property int $id
 * @property string $report_date
 * @property string $formation
 * @property string $position
 * @property string $name
 * @property string $nickname
 * @property string $phone
 * @property string $resources
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\FormationMudflowReport onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereFormation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\FormationMudflowReport withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\FormationMudflowReport withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport todayRecords()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport whereFilled()
 */
class FormationMudflowReport extends Model
{
    use SoftDeletes;
    protected $table = 'formation_mudflow_report';
    protected $guarded = ['id'];

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('report_date', date('Y-m-d'));
    }

    public function scopeWhereFilled($q)
    {
        return $q->where('formation', '!=', null)
            ->where('position', '!=', null)
            ->where('name', '!=', null)
            ->where('nickname', '!=', null)
            ->where('phone', '!=', null)
            ->where('resources', '!=', null);
    }
}