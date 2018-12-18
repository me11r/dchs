<?php

namespace App;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class FireDepartmentCheck extends Model
{
    protected $fillable = [
        'note',
        'time_begin',
        'time_end',
        'fire_department_id',
        'responsible_person',
        'is_dspt',
        'date'
    ];

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }
}
