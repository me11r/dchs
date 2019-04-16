<?php

namespace App;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LivingSectorType extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    private $types = [
        'Жилой дом(квартира)',
        'надворные постройки',
    ];

    public $attributeNames = [
        'name' => 'Наименование',
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

