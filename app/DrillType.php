<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DrillType extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket101::class, 'drill_type_id');
    }
}
