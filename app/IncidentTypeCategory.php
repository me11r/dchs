<?php

namespace App;

use App\Models\IncidentType;
use Illuminate\Database\Eloquent\Model;

class IncidentTypeCategory extends Model
{
    protected $fillable = [
        'name'
    ];

    public function incidents()
    {
        return $this->hasMany(IncidentType::class, 'category_id');
    }
}
