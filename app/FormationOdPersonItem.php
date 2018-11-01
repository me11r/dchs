<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    ];

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
        $table = $this->table_name;
        $staff_tables = $this->report->od_staff;
        if(isset($staff_tables[$this->table_name])){
            $staff_entity = $staff_tables[$this->table_name]::find($this->staff_id);
            return ['name' => $staff_entity->name];
        }

        return ['name' => ''];
    }


}
