<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = [
        'name',
    ];

    public $types = [
        'Основная',
        'Специальная',
        'Вспомогательная',
    ];

    public function scopeName($q, $name)
    {
        return $q->where('name', $name);
    }
}
