<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationSaversReport
 *
 * @property int $id
 * @property int $saved_people
 * @property int $saved_children
 * @property int $fires
 * @property int $ignitions
 * @property int $emergencies
 * @property int $rescues
 * @property int $searches
 * @property int $others
 * @property int $false_calls
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $total
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereEmergencies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereFalseCalls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereFires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereIgnitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereRescues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSavedChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSavedPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereSearches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $report_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Formation\Migrations[] $migrations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Formation\Operations[] $operations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Formation\Resources[] $resources
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationMudflowReport todayRecords()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationSaversReport whereFilled()
 */
class FormationSaversReport extends Model
{
    protected $table = 'formation_savers_report';
    protected $guarded = ['id'];

    public function operations()
    {
        return $this->hasMany(Formation\Operations::class);
    }

    public function migrations()
    {
        return $this->hasMany(Formation\Migrations::class);
    }

    public function resources()
    {
        return $this->hasMany(Formation\Resources::class);
    }

    public function scopeTodayRecords($q)
    {
        return $q->whereDate('report_date', date('Y-m-d'));
    }

    public function scopeWhereFilled($q)
    {
        return $q->where('saved_people', '!=', 0)
            ->where('saved_people', '!=', 0)
            ->where('saved_children', '!=', 0)
            ->where('fires', '!=', 0)
            ->where('ignitions', '!=', 0)
            ->where('emergencies', '!=', 0)
            ->where('rescues', '!=', 0)
            ->where('searches', '!=', 0)
            ->where('others', '!=', 0)
            ->where('false_calls', '!=', 0)
            ->where('total', '!=', 0);
    }
}