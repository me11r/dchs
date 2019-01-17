<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FormationOdPersonItem
 *
 * @property int $id
 * @property int $staff_id
 * @property int $report_id
 * @property string|null $status
 * @property string|null $rank
 * @property string|null $date_to
 * @property string|null $date_from
 * @property string|null $comment
 * @property string $table_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $staff
 * @property-read \App\FormationPersonsReport $report
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem rank($rank)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FormationOdPersonItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormationOdPersonItem extends Model
{
    protected $fillable = [
        'staff_id',
        'report_id',
        'status',
        'rank',
        'date_to',
        'date_from',
        'comment',
        'table_name',
        'gsm_count',
    ];

    protected $appends = ['staff'];

    public function getStaffAttribute()
    {
        $staff_tables = $this->report->od_staff;

        if(isset($staff_tables[$this->table_name])){
            $staff_entity = $staff_tables[$this->table_name]::find($this->staff_id);
            $this->staff = $staff_entity;
            return $staff_entity;
        }

        return null;
    }

    public function report()
    {
        return $this->belongsTo(FormationPersonsReport::class, 'report_id');
    }

    public function scopeRank($q, $rank)
    {
        return $q->where('rank', $rank);
    }

    public function staff()
    {
        $staff_tables = $this->report->od_staff ?? null;
        if(isset($staff_tables[$this->table_name])){
            $staff_entity = $staff_tables[$this->table_name]::find($this->staff_id);
            #$this->staff = $staff_entity;
            return ['name' => $staff_entity->name];
        }

        return ['name' => ''];
    }


}
