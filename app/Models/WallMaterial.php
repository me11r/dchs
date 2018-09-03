<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WallMaterial extends Model
{
    protected $fillable = [
        'name',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class, 'wall_material_id');
    }

    public function scopeName($q, $title)
    {
        return $q->where('name', $title);
    }
}
