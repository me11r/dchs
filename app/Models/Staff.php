<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'department_id',
        'name',
        'date_birth',
        'rank',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(FireDepartment::class, 'department_id');
    }


}
