<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Trunk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrunkType extends BaseModel
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

    public function event_info_arrived()
    {
        return $this->hasMany(EventInfoArrived::class, 'trunk_type_id');
    }
}
