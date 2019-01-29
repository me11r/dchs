<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NormPsp extends Model
{
    protected $fillable = [
        'date',
        'time_begin',
        'time_end',
        'fire_department_id',
        'department',
        'norm_number_id',
        'norm_type_id',
        'responsible_person',
    ];

    public function norm_type()
    {
        return $this->belongsTo(NormType::class, 'norm_type_id');
    }

    public function norm_number()
    {
        return $this->belongsTo(NormNumber::class, 'norm_number_id');
    }

    public function fire_department()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }
}
