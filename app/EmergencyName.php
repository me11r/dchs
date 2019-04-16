<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmergencyName extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $attributeNames = [
        'name' => 'Наименование',
    ];

    public function card112()
    {
        return $this->belongsTo(\Card112::class,'emergency_name_id');
    }

    public function scopeName($q, $search)
    {
        return $q->where('name', $search);
    }
}
