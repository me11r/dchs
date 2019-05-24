<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Card112\Card112;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentPlace extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function cards112()
    {
        return $this->hasMany(Card112::class, 'additional_incident_place_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
