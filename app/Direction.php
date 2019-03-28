<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'reserved', // ЗПУ-запасной пункт управления
        'sort_order',
    ];

    public function alert_system_checks()
    {
        return $this->hasMany(AlertSystemCheckItem::class, 'direction_id');
    }

    public function scopeSorted($q, $direction = 'asc')
    {
        return $q->orderBy('sort_order', $direction);
    }

    public function scopeReserved($q, $search = null)
    {
        return $q->where('reserved', $search);
    }

}
