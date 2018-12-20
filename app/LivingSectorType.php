<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivingSectorType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    private $types = [
        'Жилой дом(квартира)',
        'надворные постройки',
    ];

    public function tickets101()
    {
        return $this->hasMany(Ticket101::class, 'living_sector_type_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}

