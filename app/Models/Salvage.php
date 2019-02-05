<?php

namespace App\Models;

use App\FireDepartment;
use Illuminate\Database\Eloquent\Model;

class Salvage extends Model
{
    public $table = 'salvage';

    protected $fillable = [
        'date',
        'value',
        'fire_department_id'
    ];


    public function fireDepartment()
    {
        return $this->belongsTo(FireDepartment::class, 'fire_department_id');
    }
}
