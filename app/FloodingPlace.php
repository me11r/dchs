<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FloodingPlace extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }

    public function cards112()
    {
        return $this->hasMany(\Card112::class, 'flooding_place_id');
    }
}
